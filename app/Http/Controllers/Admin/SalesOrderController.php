<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Package;
use App\Models\PackageStock;
use App\Models\SalesPackageStock;
use App\Models\PackageStockMovement;
use App\Models\SalesOrder;
use App\Models\SalesStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class SalesOrderController extends Controller
{
    /**
     * Tampilkan semua pengajuan masuk dari sales
     */
    public function index()
    {
        $orders = SalesOrder::with(['customer', 'sales'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.sales-orders.index', compact('orders'));
    }

    /**
     * Detail pengajuan
     */
    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'sales', 'items.product', 'packageItems.package.items.product']);
        return view('admin.sales-orders.show', compact('salesOrder'));
    }

    /**
     * Update status pengajuan (Setujui / Selesai / Tolak)
     */
    public function updateStatus(Request $request, SalesOrder $salesOrder)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai,dibatalkan'
        ]);

        $newStatus = $request->status;
        $oldStatus = $salesOrder->status;

        // VALIDASI PENTING: Mencegah perubahan jika sudah final
        if (in_array($oldStatus, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Status pengajuan yang sudah selesai atau ditolak tidak dapat diubah.');
        }

        DB::beginTransaction();

        try {
            // LOGIKA APPROVAL: Setujui & Potong Stok
            if ($newStatus === 'diproses') {
                // Pastikan hanya bisa dari status 'menunggu'
                if ($oldStatus !== 'menunggu') {
                    throw new Exception("Hanya pengajuan dengan status 'Menunggu' yang bisa disetujui.");
                }

                // 1. Proses item produk satuan
                foreach ($salesOrder->items as $item) {
                    // Lock product record untuk cegah race condition
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($product->current_stock < $item->qty) {
                        $currentStockFormatted = number_format($product->current_stock, 0, ',', '.');
                        $unitName = $product->unit->name ?? '';
                        throw new Exception("Stok produk '{$product->name}' tidak cukup. Sisa gudang: {$currentStockFormatted} {$unitName}");
                    }

                    // ── STEP 1: Kurangi stok GUDANG ──────────────────────────────
                    $gudangBefore = $product->current_stock;
                    $product->current_stock -= $item->qty;
                    $product->save();

                    // Movement #1 — OUT dari Gudang Utama
                    StockMovement::create([
                        'item_type'      => 'product',
                        'item_id'        => $product->id,
                        'movement_type'  => 'out',
                        'reference_type' => SalesOrder::class,
                        'reference_id'   => $salesOrder->id,
                        'qty'            => $item->qty,
                        'stock_before'   => $gudangBefore,
                        'stock_after'    => $product->current_stock,
                        'note'           => "Keluar gudang → Sales {$salesOrder->sales->name} ({$salesOrder->order_number})",
                        'user_id'        => null, // NULL = stok gudang utama
                    ]);

                    // ── STEP 2: Tambah stok SALES ────────────────────────────────
                    $salesStock = SalesStock::lockForUpdate()->firstOrCreate(
                        ['user_id' => $salesOrder->sales_id, 'product_id' => $product->id],
                        ['qty' => 0]
                    );
                    $salesStockBefore = $salesStock->qty;
                    $salesStock->increment('qty', $item->qty);
                    $salesStock->refresh();

                    // Movement #2 — IN ke Stok Sales
                    StockMovement::create([
                        'item_type'      => 'product',
                        'item_id'        => $product->id,
                        'movement_type'  => 'in',
                        'reference_type' => SalesOrder::class,
                        'reference_id'   => $salesOrder->id,
                        'qty'            => $item->qty,
                        'stock_before'   => $salesStockBefore,
                        'stock_after'    => $salesStock->qty,
                        'note'           => "Masuk stok sales dari gudang ({$salesOrder->order_number})",
                        'user_id'        => $salesOrder->sales_id,
                    ]);
                }

                // 2. Proses item paket fisik
                foreach ($salesOrder->packageItems as $item) {
                    // Lock package stock record gudang
                    $packageStock = PackageStock::where('package_id', $item->package_id)->lockForUpdate()->first();
                    $package = Package::find($item->package_id);

                    $currentStock = $packageStock ? $packageStock->qty : 0.00;
                    if ($currentStock < $item->qty) {
                        $currentStockFormatted = number_format($currentStock, 0, ',', '.');
                        throw new Exception("Stok paket '{$package->name}' tidak cukup. Sisa gudang: {$currentStockFormatted} pack");
                    }

                    // ── STEP 1: Kurangi stok gudang utama paket
                    $gudangBefore = $packageStock->qty;
                    $packageStock->qty -= $item->qty;
                    $packageStock->save();

                    // Movement #1 — OUT dari Gudang Utama Paket
                    PackageStockMovement::create([
                        'package_id'     => $package->id,
                        'user_id'        => null, // Gudang Utama
                        'movement_type'  => 'out',
                        'qty'            => $item->qty,
                        'stock_before'   => $gudangBefore,
                        'stock_after'    => $packageStock->qty,
                        'reference_type' => SalesOrder::class,
                        'reference_id'   => $salesOrder->id,
                        'note'           => "Keluar gudang -> Sales {$salesOrder->sales->name} ({$salesOrder->order_number})",
                        'created_by'     => Auth::id(),
                    ]);

                    // ── STEP 2: Tambah stok paket SALES
                    $salesPackageStock = SalesPackageStock::where('user_id', $salesOrder->sales_id)
                        ->where('package_id', $package->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$salesPackageStock) {
                        $salesPackageStock = SalesPackageStock::create([
                            'user_id'    => $salesOrder->sales_id,
                            'package_id' => $package->id,
                            'qty'        => 0.00,
                        ]);
                    }

                    $salesStockBefore = $salesPackageStock->qty;
                    $salesPackageStock->qty += $item->qty;
                    $salesPackageStock->save();

                    // Movement #2 — IN/Transfer to Sales
                    PackageStockMovement::create([
                        'package_id'     => $package->id,
                        'user_id'        => $salesOrder->sales_id,
                        'movement_type'  => 'transfer_to_sales',
                        'qty'            => $item->qty,
                        'stock_before'   => $salesStockBefore,
                        'stock_after'    => $salesPackageStock->qty,
                        'reference_type' => SalesOrder::class,
                        'reference_id'   => $salesOrder->id,
                        'note'           => "Masuk stok sales dari gudang ({$salesOrder->order_number})",
                        'created_by'     => Auth::id(),
                    ]);
                }

                $salesOrder->processed_at = now();
            }

            // Jika Selesai
            if ($newStatus === 'selesai') {
                if ($oldStatus !== 'diproses') {
                     throw new Exception("Pengajuan harus disetujui/diproses terlebih dahulu sebelum diselesaikan.");
                }
                $salesOrder->completed_at = now();
            }

            // Jika Dibatalkan / Ditolak
            if ($newStatus === 'dibatalkan') {
                if ($oldStatus !== 'menunggu') {
                    throw new Exception("Hanya pengajuan dengan status 'Menunggu' yang bisa ditolak.");
                }
                $salesOrder->cancelled_at = now();
            }

            $salesOrder->status = $newStatus;
            $salesOrder->save();

            DB::commit();

            $message = $newStatus === 'diproses' ? 'Pengajuan berhasil disetujui dan stok telah dikurangi.' : 'Status pengajuan berhasil diperbarui.';
            return redirect()->route('admin.sales-orders.show', $salesOrder)->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Nota Pengambilan Barang Sales untuk Arsip Gudang
     */
    public function printPickupNote(SalesOrder $salesOrder)
    {
        // Validasi status harus approved (diproses / selesai)
        if (!in_array($salesOrder->status, ['diproses', 'selesai'])) {
            abort(403, 'Nota pengambilan hanya dapat dicetak untuk pengajuan yang sudah disetujui.');
        }

        $salesOrder->load([
            'sales',
            'customer',
            'items.product.unit',
            'packageItems.package'
        ]);

        return view('admin.sales-orders.pickup-note', compact('salesOrder'));
    }
}

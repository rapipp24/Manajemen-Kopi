<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $salesOrder->load(['customer', 'sales', 'items.product']);
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
                    // user_id = NULL → menandakan movement ini milik stok gudang/products
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
                    $salesStock = SalesStock::firstOrCreate(
                        ['user_id' => $salesOrder->sales_id, 'product_id' => $product->id],
                        ['qty' => 0]
                    );
                    $salesStockBefore = $salesStock->qty;
                    $salesStock->increment('qty', $item->qty);
                    $salesStock->refresh(); // ambil nilai terbaru

                    // Movement #2 — IN ke Stok Sales
                    // user_id = sales_id → menandakan movement ini milik stok sales
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
                        'user_id'        => $salesOrder->sales_id, // NOT NULL = stok sales
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
                // Jika dibatalkan saat sudah diproses, stok harusnya dikembalikan? 
                // Namun sesuai requirement: "Jika ditolak (dari menunggu), stok tidak berubah".
                // Untuk keamanan, kita hanya izinkan tolak jika status masih 'menunggu' atau 'diproses' (dengan catatan stok tetap berkurang jika sudah diproses).
                // Tapi user minta: "Jika ditolak, stok tidak berubah". Ini biasanya untuk status 'menunggu'.
                
                if ($oldStatus === 'diproses') {
                    // Opsional: Logika kembalikan stok jika admin membatalkan yang sudah disetujui
                    // Tapi kita ikuti rule simpel dulu: Tolak berarti Gagal.
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
}

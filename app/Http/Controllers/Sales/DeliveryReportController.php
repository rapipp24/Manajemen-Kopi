<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\SalesStock;
use App\Models\StockMovement;
use App\Models\SalesPackageStock;
use App\Models\DeliveryReportPackageItem;
use App\Models\PackageAssembly;
use App\Models\PackageItem;
use App\Models\PackageStockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class DeliveryReportController extends Controller
{
    /**
     * Daftar laporan pengiriman milik sales yang login
     */
    public function index()
    {
        $reports = DeliveryReport::with(['customer'])
            ->where('sales_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.delivery-reports.index', compact('reports'));
    }

    /**
     * Form buat laporan pengiriman baru.
     * Produk hanya dari stok sales milik sendiri.
     * Customer list sebagai referensi opsional (dropdown tidak wajib).
     */
    public function create()
    {
        // Dropdown customer dari master — opsional
        $customers = Customer::orderBy('name')->get();

        // Stok sales yang qty > 0
        $salesStocks = SalesStock::with('product.unit')
            ->where('user_id', Auth::id())
            ->where('qty', '>', 0)
            ->get();

        // Stok paket sales yang qty > 0 dan aktif
        $salesPackageStocks = SalesPackageStock::where('user_id', Auth::id())
            ->where('qty', '>', 0)
            ->whereHas('package', function($query) {
                $query->where('is_active', true);
            })
            ->with(['package.items.product'])
            ->get();

        return view('sales.delivery-reports.create', compact('customers', 'salesStocks', 'salesPackageStocks'));
    }

    /**
     * Simpan laporan pengiriman dan kurangi stok sales.
     * Stok gudang TIDAK disentuh.
     */
    public function store(Request $request)
    {
        // Filter items dan package_items sebelum validasi untuk membuang baris kosong/default
        $productItems = collect($request->input('items', []))
            ->filter(fn ($item) => filled($item['product_id'] ?? null) && (float)($item['qty'] ?? 0) > 0)
            ->values()
            ->toArray();

        $packageItems = collect($request->input('package_items', []))
            ->filter(fn ($item) => filled($item['package_id'] ?? null) && (int)($item['qty'] ?? 0) > 0)
            ->values()
            ->toArray();

        // Overwrite request input dengan data yang sudah di-filter
        $request->merge([
            'items' => $productItems,
            'package_items' => $packageItems,
        ]);

        $request->validate([
            // Toko tujuan: boleh dari master ATAU input manual
            'customer_id'              => 'nullable|exists:customers,id',
            'customer_name_manual'     => 'required_without:customer_id|nullable|string|max:255',
            'customer_address_manual'  => 'required_without:customer_id|nullable|string|max:500',
            'customer_phone_manual'    => 'required_without:customer_id|nullable|string|max:20',
            'payment_term_days'        => 'nullable|integer|in:15,30',
            // Info pengiriman
            'delivery_date'            => 'required|date',
            'note'                     => 'nullable|string',
            // Produk satuan (opsional)
            'items'                    => 'nullable|array',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.qty'              => 'required|numeric|min:0.01',
            // Paket (opsional)
            'package_items'              => 'nullable|array',
            'package_items.*.package_id' => 'required|exists:packages,id',
            'package_items.*.qty'        => 'required|integer|min:1',
        ], [
            'customer_name_manual.required_without'    => 'Nama toko wajib diisi jika tidak memilih dari daftar customer.',
            'customer_address_manual.required_without' => 'Alamat toko wajib diisi jika tidak memilih dari daftar customer.',
            'customer_phone_manual.required_without'   => 'No. telepon toko wajib diisi jika tidak memilih dari daftar customer.',
        ]);

        // Validasi minimal 1 produk atau 1 paket
        $hasProducts = count($productItems) > 0;
        $hasPackages = count($packageItems) > 0;

        if (!$hasProducts && !$hasPackages) {
            return back()->withInput()->with('error', 'Laporan pengiriman harus berisi minimal 1 produk atau 1 paket.');
        }

        // Validasi tidak boleh duplikat produk dalam satu form
        if ($hasProducts) {
            $productIds = collect($request->items)->pluck('product_id')->toArray();
            if (count($productIds) !== count(array_unique($productIds))) {
                return back()->withInput()->with('error', 'Tidak boleh ada produk yang sama diinput lebih dari sekali.');
            }
        }

        // Validasi tidak boleh duplikat paket dalam satu form
        if ($hasPackages) {
            $packageIds = collect($request->package_items)->pluck('package_id')->toArray();
            if (count($packageIds) !== count(array_unique($packageIds))) {
                return back()->withInput()->with('error', 'Tidak boleh ada paket yang sama diinput lebih dari sekali.');
            }
        }

        DB::beginTransaction();

        try {
            // Semua Delivery Report baru selalu dimulai belum_bayar.
            $dpAmount = 0;

            // Hitung due_date otomatis jika tempo ada
            $dueDate = null;
            if ($request->payment_term_days) {
                $dueDate = \Carbon\Carbon::parse($request->delivery_date)
                    ->addDays((int)$request->payment_term_days)
                    ->format('Y-m-d');
            }

            // Generate nomor laporan: DEL-YYYYMMDD-XXX
            $date  = date('Ymd');
            $count = DeliveryReport::whereDate('created_at', today())->count() + 1;
            $reportNumber = 'DEL-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $report = DeliveryReport::create([
                'report_number'           => $reportNumber,
                'sales_id'                => Auth::id(),
                'customer_id'             => $request->customer_id ?: null,
                'customer_name_manual'    => $request->customer_id ? null : $request->customer_name_manual,
                'customer_address_manual' => $request->customer_id ? null : $request->customer_address_manual,
                'customer_phone_manual'   => $request->customer_id ? null : $request->customer_phone_manual,
                'payment_term_days'       => $request->payment_term_days ?: null,
                'delivery_date'           => $request->delivery_date,
                'note'                    => $request->note,
                'status'                  => 'submitted',
                'total_amount'            => 0, // Akan diupdate
                'payment_status'          => 'belum_bayar',
                'down_payment_amount'     => 0,
                'due_date'                => $dueDate,
                'created_by'              => Auth::id(),
            ]);

            $totalAmount = 0;
            $tokoName = $report->toko_name;

            // A. Proses Produk Satuan
            if ($hasProducts) {
                foreach ($request->items as $item) {
                    // Lock stok sales untuk cegah race condition
                    $salesStock = SalesStock::lockForUpdate()
                        ->where('user_id', Auth::id())
                        ->where('product_id', $item['product_id'])
                        ->first();

                    // Validasi: stok harus ada dan cukup
                    if (!$salesStock) {
                        throw new Exception("Produk tidak ditemukan di stok Anda.");
                    }

                    if ($salesStock->qty < $item['qty']) {
                        $nm = $salesStock->product->name ?? 'Produk';
                        throw new Exception("Stok '{$nm}' tidak cukup. Stok Anda: {$salesStock->qty}");
                    }

                    // Ambil harga asli dari master produk
                    $actualPrice = $salesStock->product->price;
                    $subtotal = $item['qty'] * $actualPrice;

                    // Simpan item laporan
                    DeliveryReportItem::create([
                        'delivery_report_id' => $report->id,
                        'product_id'         => $item['product_id'],
                        'qty'                => $item['qty'],
                        'price'              => $actualPrice,
                        'subtotal'           => $subtotal,
                    ]);

                    // Kurangi stok sales
                    $stockBefore = $salesStock->qty;
                    $salesStock->decrement('qty', $item['qty']);
                    $salesStock->refresh();

                    // Catat stock movement — OUT dari stok sales ke toko
                    StockMovement::create([
                        'item_type'      => 'product',
                        'item_id'        => $item['product_id'],
                        'movement_type'  => 'out',
                        'reference_type' => DeliveryReport::class,
                        'reference_id'   => $report->id,
                        'qty'            => $item['qty'],
                        'stock_before'   => $stockBefore,
                        'stock_after'    => $salesStock->qty,
                        'note'           => "Keluar stok sales → {$tokoName} ({$reportNumber})",
                        'user_id'        => Auth::id(),
                    ]);

                    $totalAmount += $subtotal;
                }
            }

            // B. Proses Paket / Pack
            if ($hasPackages) {
                foreach ($request->package_items as $item) {
                    $packageId = $item['package_id'];
                    $qtyKirim = (int)$item['qty'];

                    // Lock stok paket sales
                    $salesPackageStock = SalesPackageStock::lockForUpdate()
                        ->where('user_id', Auth::id())
                        ->where('package_id', $packageId)
                        ->first();

                    if (!$salesPackageStock) {
                        throw new Exception("Paket tidak ditemukan di stok Anda.");
                    }

                    if ($salesPackageStock->qty < $qtyKirim) {
                        $nm = $salesPackageStock->package->name ?? 'Paket';
                        throw new Exception("Stok paket '{$nm}' tidak cukup. Stok Anda: {$salesPackageStock->qty} pack.");
                    }

                    $package = $salesPackageStock->package;
                    if (!$package) {
                        throw new Exception("Data master paket tidak ditemukan.");
                    }

                    $actualPrice = (float)$package->selling_price;
                    $subtotal = $qtyKirim * $actualPrice;

                    // Hitung HPP Snapshot (Fase 4A belum batch-aware)
                    $hppPerPackage = 0.00;
                    $latestAssembly = PackageAssembly::where('package_id', $packageId)
                        ->latest()
                        ->first();

                    if ($latestAssembly) {
                        $hppPerPackage = (float)$latestAssembly->hpp_per_package_snapshot;
                    } else {
                        // Fallback: hitung dari komponen jika data assembly tidak ditemukan
                        $packageItems = PackageItem::with('product')->where('package_id', $packageId)->get();
                        foreach ($packageItems as $pkgItem) {
                            $productCost = $pkgItem->product ? (float)$pkgItem->product->cost_price : 0.00;
                            $hppPerPackage += (float)$pkgItem->qty * $productCost;
                        }
                    }

                    // Simpan rincian paket
                    DeliveryReportPackageItem::create([
                        'delivery_report_id'    => $report->id,
                        'package_id'            => $packageId,
                        'qty'                   => $qtyKirim,
                        'price'                 => $actualPrice,
                        'subtotal'              => $subtotal,
                        'package_name_snapshot' => $package->name,
                        'package_code_snapshot' => $package->code,
                        'package_hpp_snapshot'  => $hppPerPackage,
                    ]);

                    // Kurangi stok paket sales
                    $stockBefore = $salesPackageStock->qty;
                    $salesPackageStock->decrement('qty', $qtyKirim);
                    $salesPackageStock->refresh();

                    // Catat mutasi stok paket (movement_type = 'sale')
                    PackageStockMovement::create([
                        'package_id'     => $packageId,
                        'user_id'        => Auth::id(),
                        'movement_type'  => 'sale',
                        'qty'            => $qtyKirim,
                        'stock_before'   => $stockBefore,
                        'stock_after'    => $salesPackageStock->qty,
                        'reference_type' => DeliveryReport::class,
                        'reference_id'   => $report->id,
                        'note'           => "Keluar stok sales → {$tokoName} ({$reportNumber})",
                        'created_by'     => Auth::id(),
                    ]);

                    $totalAmount += $subtotal;
                }
            }

            // Update nilai final di laporan
            $report->update([
                'total_amount'        => $totalAmount,
                'down_payment_amount' => $dpAmount,
            ]);

            $report->refresh();

            // Guard pencegahan untuk memastikan data tersimpan dengan benar dan menghindari total_amount = 0
            if ($totalAmount <= 0) {
                throw new Exception("Total laporan tidak valid. Silakan coba simpan ulang.");
            }

            if (abs((float)$report->total_amount - $totalAmount) > 0.01) {
                throw new Exception("Total laporan tidak valid. Silakan coba simpan ulang.");
            }

            DB::commit();

            return redirect()
                ->route('sales.delivery-reports.show', $report)
                ->with('success', "Laporan {$reportNumber} berhasil disimpan.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Detail laporan — sales hanya bisa lihat miliknya
     */
    public function show(DeliveryReport $deliveryReport)
    {
        if ($deliveryReport->sales_id !== Auth::id()) {
            abort(403);
        }

        $deliveryReport->load(['customer', 'items.product.unit', 'packageItems.package.items.product']);
        return view('sales.delivery-reports.show', compact('deliveryReport'));
    }
}

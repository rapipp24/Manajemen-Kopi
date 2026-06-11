<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class SalesReturnController extends Controller
{
    /**
     * Daftar return milik sales yang sedang login.
     */
    public function index()
    {
        $returns = SalesReturn::with(['deliveryReport'])
            ->where('sales_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.returns.index', compact('returns'));
    }

    /**
     * Form buat return baru.
     * Sales pilih Delivery Report, lalu pilih item + qty yang direturn.
     */
    public function create(Request $request)
    {
        // Ambil laporan pengiriman milik sales ini
        $reports = DeliveryReport::where('sales_id', Auth::id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedReport = null;
        $itemsWithMaxReturn = collect();
        $packageItemsWithMaxReturn = collect();

        if ($request->filled('delivery_report_id')) {
            $selectedReport = DeliveryReport::where('sales_id', Auth::id())
                ->with(['items.product', 'packageItems'])
                ->findOrFail($request->delivery_report_id);

            // Hitung qty maksimal yang bisa direturn per item
            $itemsWithMaxReturn = $selectedReport->items->map(function ($item) {
                // Jumlah yang sudah diterima + masih menunggu dari pengajuan sebelumnya
                $qtyAlreadyReturned = SalesReturnItem::whereHas('salesReturn', function ($q) use ($item) {
                    $q->where('delivery_report_id', $item->delivery_report_id)
                      ->whereIn('status', ['menunggu', 'diterima']);
                })
                ->where('delivery_report_item_id', $item->id)
                ->sum('qty_return');

                $item->max_return = max(0, $item->qty - $qtyAlreadyReturned);
                return $item;
            })->filter(fn($item) => $item->max_return > 0); // hanya tampilkan item yang masih bisa direturn

            // Hitung qty maksimal paket yang bisa direturn
            $packageItemsWithMaxReturn = $selectedReport->packageItems->map(function ($pkgItem) {
                // Jumlah paket yang sudah diterima + masih menunggu dari pengajuan sebelumnya
                $qtyAlreadyReturned = \App\Models\SalesReturnPackageItem::whereHas('salesReturn', function ($q) use ($pkgItem) {
                    $q->where('delivery_report_id', $pkgItem->delivery_report_id)
                      ->whereIn('status', ['menunggu', 'diterima']);
                })
                ->where('delivery_report_package_item_id', $pkgItem->id)
                ->sum('qty');

                $pkgItem->max_return = max(0, $pkgItem->qty - $qtyAlreadyReturned);
                return $pkgItem;
            })->filter(fn($item) => $item->max_return > 0);
        }

        return view('sales.returns.create', compact('reports', 'selectedReport', 'itemsWithMaxReturn', 'packageItemsWithMaxReturn'));
    }

    /**
     * Simpan pengajuan return baru.
     * Saat ini: stok tidak berubah, tagihan tidak berubah.
     */
    public function store(Request $request)
    {
        // Filter baris kosong/default untuk produk satuan
        $productItems = collect($request->input('items', []))
            ->filter(fn ($item) => filled($item['delivery_report_item_id'] ?? null) && filled($item['qty_return'] ?? null) && $item['qty_return'] !== '' && $item['qty_return'] != 0)
            ->values()
            ->toArray();

        // Filter baris kosong/default untuk paket
        $packageItems = collect($request->input('package_items', []))
            ->filter(fn ($item) => filled($item['delivery_report_package_item_id'] ?? null) && filled($item['qty'] ?? null) && $item['qty'] !== '' && $item['qty'] != 0)
            ->values()
            ->toArray();

        // Overwrite request input
        $request->merge([
            'items' => $productItems,
            'package_items' => $packageItems,
        ]);

        $hasProducts = count($productItems) > 0;
        $hasPackages = count($packageItems) > 0;

        if (!$hasProducts && !$hasPackages) {
            return back()->withInput()->with('error', 'Minimal pilih satu produk atau paket untuk direturn.');
        }

        $request->validate([
            'delivery_report_id'   => 'required|exists:delivery_reports,id',
            'return_date'          => 'required|date',
            'return_type'          => 'required|string|in:tukar_barang,potong_tagihan',
            'note'                 => 'nullable|string|max:1000',
            
            // Produk satuan (opsional)
            'items'                => 'nullable|array',
            'items.*.delivery_report_item_id' => 'required|exists:delivery_report_items,id',
            'items.*.qty_return'   => 'required|integer|min:1',
            'items.*.reason'       => 'nullable|string|max:255',

            // Paket (opsional)
            'package_items'        => 'nullable|array',
            'package_items.*.delivery_report_package_item_id' => 'required|exists:delivery_report_package_items,id',
            'package_items.*.qty'        => 'required|integer|min:1',
            'package_items.*.condition'  => 'required|string|in:layak_jual,tidak_layak_jual,perlu_proses_ulang',
            'package_items.*.reason'     => 'nullable|string|max:255',
        ]);

        // Pastikan laporan milik sales ini
        $report = DeliveryReport::where('id', $request->delivery_report_id)
            ->where('sales_id', Auth::id())
            ->firstOrFail();

        // Validasi duplikat package items
        if ($hasPackages) {
            $packageItemIds = collect($packageItems)->pluck('delivery_report_package_item_id')->toArray();
            if (count($packageItemIds) !== count(array_unique($packageItemIds))) {
                return back()->withInput()->with('error', 'Tidak boleh ada paket yang sama diinput lebih dari sekali.');
            }
        }

        // Validasi duplikat product items
        if ($hasProducts) {
            $productItemIds = collect($productItems)->pluck('delivery_report_item_id')->toArray();
            if (count($productItemIds) !== count(array_unique($productItemIds))) {
                return back()->withInput()->with('error', 'Tidak boleh ada produk yang sama diinput lebih dari sekali.');
            }
        }

        // Validasi setiap item produk satuan
        $validatedItems = [];
        if ($hasProducts) {
            foreach ($productItems as $itemData) {
                $drItem = DeliveryReportItem::where('id', $itemData['delivery_report_item_id'])
                    ->where('delivery_report_id', $report->id)
                    ->firstOrFail();

                $qtyAlreadyReturned = SalesReturnItem::whereHas('salesReturn', function ($q) use ($report) {
                    $q->where('delivery_report_id', $report->id)
                      ->whereIn('status', ['menunggu', 'diterima']);
                })
                ->where('delivery_report_item_id', $drItem->id)
                ->sum('qty_return');

                $maxReturn = $drItem->qty - $qtyAlreadyReturned;

                if ($itemData['qty_return'] > $maxReturn) {
                    return back()->withInput()->with('error',
                        'Qty return untuk produk "' . $drItem->product->name . '" melebihi batas. ' .
                        'Maksimal yang bisa direturn: ' . $maxReturn . ' pcs.'
                    );
                }

                $validatedItems[] = [
                    'delivery_report_item_id' => $drItem->id,
                    'product_id'              => $drItem->product_id,
                    'qty_return'              => (int) $itemData['qty_return'],
                    'price_snapshot'          => $drItem->price,
                    'subtotal_return'         => $drItem->price * $itemData['qty_return'],
                    'reason'                  => $itemData['reason'] ?? null,
                ];
            }
        }

        // Validasi setiap item paket
        $validatedPackageItems = [];
        if ($hasPackages) {
            foreach ($packageItems as $itemData) {
                $drPkgItem = \App\Models\DeliveryReportPackageItem::where('id', $itemData['delivery_report_package_item_id'])
                    ->where('delivery_report_id', $report->id)
                    ->firstOrFail();

                $qtyAlreadyReturned = \App\Models\SalesReturnPackageItem::whereHas('salesReturn', function ($q) use ($report) {
                    $q->where('delivery_report_id', $report->id)
                      ->whereIn('status', ['menunggu', 'diterima']);
                })
                ->where('delivery_report_package_item_id', $drPkgItem->id)
                ->sum('qty');

                $maxReturn = $drPkgItem->qty - $qtyAlreadyReturned;

                if ($itemData['qty'] > $maxReturn) {
                    return back()->withInput()->with('error',
                        'Qty return untuk paket "' . $drPkgItem->package_name_snapshot . '" melebihi batas. ' .
                        'Maksimal yang bisa direturn: ' . $maxReturn . ' pack.'
                    );
                }

                $validatedPackageItems[] = [
                    'delivery_report_package_item_id' => $drPkgItem->id,
                    'package_id'                      => $drPkgItem->package_id,
                    'qty'                             => (int) $itemData['qty'],
                    'price'                           => $drPkgItem->price,
                    'subtotal'                        => $drPkgItem->price * $itemData['qty'],
                    'package_name_snapshot'           => $drPkgItem->package_name_snapshot,
                    'package_code_snapshot'           => $drPkgItem->package_code_snapshot,
                    'package_hpp_snapshot'            => $drPkgItem->package_hpp_snapshot,
                    'condition'                       => $itemData['condition'],
                    'replacement_note'                => $itemData['reason'] ?? null,
                ];
            }
        }

        // Buat return header
        $returnNumber = 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            $salesReturn = SalesReturn::create([
                'return_number'      => $returnNumber,
                'delivery_report_id' => $report->id,
                'sales_id'           => Auth::id(),
                'return_date'        => $request->return_date,
                'status'             => 'menunggu',
                'note'               => $request->note,
                'return_type'        => $request->return_type,
            ]);

            // Buat item-item return produk
            if ($hasProducts) {
                foreach ($validatedItems as $item) {
                    $salesReturn->items()->create($item);
                }
            }

            // Buat item-item return paket
            if ($hasPackages) {
                foreach ($validatedPackageItems as $item) {
                    $salesReturn->packageItems()->create($item);
                }
            }

            DB::commit();

            return redirect()->route('sales.returns.show', $salesReturn)
                ->with('success', 'Pengajuan return berhasil dibuat dan menunggu verifikasi admin.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat return: ' . $e->getMessage());
        }
    }

    /**
     * Detail return milik sales.
     */
    public function show(SalesReturn $return)
    {
        // Pastikan hanya bisa melihat return miliknya
        if ($return->sales_id !== Auth::id()) {
            abort(403);
        }

        $return->load(['deliveryReport', 'items.product', 'packageItems.package', 'approver']);

        return view('sales.returns.show', compact('return'));
    }
}

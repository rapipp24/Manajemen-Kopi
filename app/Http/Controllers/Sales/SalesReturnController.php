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

        if ($request->filled('delivery_report_id')) {
            $selectedReport = DeliveryReport::where('sales_id', Auth::id())
                ->with('items.product')
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
        }

        return view('sales.returns.create', compact('reports', 'selectedReport', 'itemsWithMaxReturn'));
    }

    /**
     * Simpan pengajuan return baru.
     * Saat ini: stok tidak berubah, tagihan tidak berubah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_report_id'   => 'required|exists:delivery_reports,id',
            'return_date'          => 'required|date',
            'return_type'          => 'required|string|in:tukar_barang,potong_tagihan',
            'note'                 => 'nullable|string|max:1000',
            'items'                => 'required|array|min:1',
            'items.*.delivery_report_item_id' => 'required|exists:delivery_report_items,id',
            'items.*.qty_return'   => 'required|integer|min:1',
            'items.*.reason'       => 'nullable|string|max:255',
        ]);

        // Pastikan laporan milik sales ini
        $report = DeliveryReport::where('id', $request->delivery_report_id)
            ->where('sales_id', Auth::id())
            ->firstOrFail();

        // Validasi setiap item
        $validatedItems = [];
        foreach ($request->items as $idx => $itemData) {
            // Ambil item delivery report yang valid (harus milik laporan yang dipilih)
            $drItem = DeliveryReportItem::where('id', $itemData['delivery_report_item_id'])
                ->where('delivery_report_id', $report->id)
                ->firstOrFail();

            // Hitung qty yang sudah direturn (menunggu + diterima)
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
                'price_snapshot'          => $drItem->price, // harga asli dari laporan, bukan master produk
                'subtotal_return'         => $drItem->price * $itemData['qty_return'],
                'reason'                  => $itemData['reason'] ?? null,
            ];
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

            // Buat item-item return
            foreach ($validatedItems as $item) {
                $salesReturn->items()->create($item);
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

        $return->load(['deliveryReport', 'items.product', 'approver']);

        return view('sales.returns.show', compact('return'));
    }
}

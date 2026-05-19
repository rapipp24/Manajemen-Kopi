<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryReport;
use App\Models\DeliveryReportItem;
use App\Models\SalesStock;
use App\Models\StockMovement;
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

        return view('sales.delivery-reports.create', compact('customers', 'salesStocks'));
    }

    /**
     * Simpan laporan pengiriman dan kurangi stok sales.
     * Stok gudang TIDAK disentuh.
     */
    public function store(Request $request)
    {
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
            // Pembayaran: tidak diterima dari frontend
            // payment_status dan down_payment_amount di-force oleh backend
            // Produk
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.qty'              => 'required|numeric|min:0.01',
        ], [
            'customer_name_manual.required_without'    => 'Nama toko wajib diisi jika tidak memilih dari daftar customer.',
            'customer_address_manual.required_without' => 'Alamat toko wajib diisi jika tidak memilih dari daftar customer.',
            'customer_phone_manual.required_without'   => 'No. telepon toko wajib diisi jika tidak memilih dari daftar customer.',
            'payment_status.required'                  => 'Status pembayaran wajib dipilih.',
        ]);

        DB::beginTransaction();

        try {
            // Semua Delivery Report baru selalu dimulai belum_bayar.
            // Pembayaran DP/Lunas harus melalui Setoran Uang (sales_deposits) + verifikasi Admin.
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
            $tokoName = $report->toko_name; // Dari accessor: customer->name atau customer_name_manual

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

                // Kurangi stok sales — stok gudang TIDAK disentuh
                $stockBefore = $salesStock->qty;
                $salesStock->decrement('qty', $item['qty']);
                $salesStock->refresh();

                // Catat stock movement — OUT dari stok sales ke toko
                // user_id = NOT NULL → movement milik stok sales
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

            // down_payment_amount tetap 0 untuk laporan baru.
            // Pembayaran dicatat terpisah via sales_deposits.

            // Update nilai final di laporan
            $report->update([
                'total_amount'        => $totalAmount,
                'down_payment_amount' => $dpAmount,
            ]);

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

        $deliveryReport->load(['customer', 'items.product.unit']);
        return view('sales.delivery-reports.show', compact('deliveryReport'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleController extends Controller
{
    /**
     * Tampilkan daftar penjualan
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'creator'])
            ->orderBy('sale_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Form tambah penjualan baru
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        // Hanya tampilkan produk yang aktif dan stoknya > 0
        $products = Product::with('unit')->where('is_active', true)->where('current_stock', '>', 0)->orderBy('name')->get();

        return view('admin.sales.create', compact('customers', 'products'));
    }

    /**
     * Simpan transaksi penjualan
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'sale_date'          => 'required|date',
            'customer_id'        => 'nullable|exists:customers,id',
            'customer_name'      => 'nullable|string|max:255',
            'payment_status'     => 'required|in:lunas,belum_bayar,sebagian',
            'payment_method'     => 'required|in:cash,transfer,qris,cod',
            'paid_amount'        => 'required|numeric|min:0',
            'note'               => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 2. Generate nomor invoice: INV-YYYYMMDD-XXX
            $date  = date('Ymd');
            $count = Sale::whereDate('created_at', today())->count() + 1;
            $invoiceNumber = 'INV-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // 3. Simpan header Sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $request->customer_id,
                'customer_name'  => $request->customer_name ?: ($request->customer_id ? Customer::find($request->customer_id)->name : 'Pelanggan Umum'),
                'sale_date'      => $request->sale_date,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'total_amount'   => 0, // Akan di-update setelah kalkulasi item
                'note'           => $request->note,
                'created_by'     => Auth::id(),
            ]);

            $totalAmount = 0;

            // 4. Proses tiap item
            foreach ($request->items as $item) {
                // Lock produk untuk mencegah race condition stok
                $product = Product::lockForUpdate()->find($item['product_id']);

                $qty = $item['qty'];
                $price = $product->price; // Ambil harga dari master produk
                $subtotal = $qty * $price;

                // 4a. Validasi stok cukup
                if ($product->current_stock < $qty) {
                    throw new Exception("Stok produk '{$product->name}' tidak cukup. Sisa stok: {$product->current_stock}");
                }

                // 4b. Simpan SaleItem
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'qty'        => $qty,
                    'price'      => $price,
                    'subtotal'   => $subtotal,
                ]);

                $totalAmount += $subtotal;

                // 4c. Update stok produk
                $stockBefore = $product->current_stock;
                $product->current_stock -= $qty;
                $product->save();

                // 4d. Catat StockMovement OUT
                StockMovement::create([
                    'item_type'      => 'product',
                    'item_id'        => $product->id,
                    'movement_type'  => 'out',
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                    'qty'            => $qty,
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $product->current_stock,
                    'note'           => "Penjualan {$invoiceNumber}",
                ]);
            }

            // 5. Update total_amount di header Sale
            $sale->update([
                'total_amount' => $totalAmount
            ]);

            // 6. Simpan Pembayaran Awal (jika ada)
            if ($request->paid_amount > 0) {
                SalePayment::create([
                    'sale_id'        => $sale->id,
                    'amount'         => $request->paid_amount,
                    'payment_date'   => $request->sale_date,
                    'payment_method' => $request->payment_method,
                    'note'           => 'Pembayaran awal / DP',
                    'created_by'     => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.sales.show', $sale)->with('success', "Transaksi {$invoiceNumber} berhasil disimpan.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Simpan pembayaran baru (pelunasan/cicilan)
     */
    public function storePayment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,transfer,qris,cod',
            'note'           => 'nullable|string',
        ]);

        // Cek sisa hutang
        $remaining = $sale->remaining_balance;
        if ($request->amount > $remaining) {
            return back()->with('error', "Jumlah bayar (Rp " . number_format($request->amount) . ") melebihi sisa hutang (Rp " . number_format($remaining) . ").");
        }

        DB::beginTransaction();
        try {
            SalePayment::create([
                'sale_id'        => $sale->id,
                'amount'         => $request->amount,
                'payment_date'   => $request->payment_date,
                'payment_method' => $request->payment_method,
                'note'           => $request->note,
                'created_by'     => Auth::id(),
            ]);

            // Update status jika sudah lunas
            if ($sale->fresh()->remaining_balance <= 0) {
                $sale->update(['payment_status' => 'lunas']);
            } else {
                $sale->update(['payment_status' => 'sebagian']);
            }

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail penjualan
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'creator', 'items.product', 'payments.creator']);
        return view('admin.sales.show', compact('sale'));
    }

    /**
     * Cetak nota penjualan
     */
    public function print(Sale $sale)
    {
        $sale->load(['customer', 'creator', 'items.product', 'payments']);
        
        $settings = [
            'shop_name'    => \App\Models\Setting::get('shop_name', 'MANAJEMEN KOPI'),
            'shop_address' => \App\Models\Setting::get('shop_address', 'Jl. Kopi Nikmat No. 123, Indonesia'),
            'shop_phone'   => \App\Models\Setting::get('shop_phone', '(021) 1234-5678'),
            'shop_email'   => \App\Models\Setting::get('shop_email', 'hello@kopimanajer.com'),
            'footer_note'  => \App\Models\Setting::get('footer_note', 'Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.'),
        ];

        return view('admin.sales.print', compact('sale', 'settings'));
    }
}

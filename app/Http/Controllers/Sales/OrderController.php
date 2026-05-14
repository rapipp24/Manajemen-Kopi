<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar pengajuan milik sales yang login
     */
    public function index()
    {
        $orders = SalesOrder::with(['customer'])
            ->where('sales_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.orders.index', compact('orders'));
    }

    /**
     * Form buat pengajuan baru
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('sales.orders.create', compact('customers', 'products'));
    }

    /**
     * Simpan pengajuan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'        => 'nullable|exists:customers,id',
            'catatan'            => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Generate Request Number: REQ-YYYYMMDD-XXX
            $date = date('Ymd');
            $count = SalesOrder::whereDate('created_at', today())->count() + 1;
            $orderNumber = 'REQ-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $order = SalesOrder::create([
                'order_number' => $orderNumber,
                'sales_id'     => Auth::id(),
                'customer_id'  => $request->customer_id,
                'status'       => 'menunggu', // Menunggu Persetujuan
                'catatan'      => $request->catatan,
                'total'        => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $item['qty'] * $product->price;

                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'harga'          => $product->price, // Snapshot harga
                    'subtotal'       => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            DB::commit();

            return redirect()->route('sales.orders.index')->with('success', "Pengajuan barang {$orderNumber} berhasil dikirim.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Detail status pengajuan
     */
    public function show(SalesOrder $order)
    {
        // Pastikan sales hanya bisa lihat pengajuan miliknya
        if ($order->sales_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['customer', 'items.product']);
        return view('sales.orders.show', compact('order'));
    }
}

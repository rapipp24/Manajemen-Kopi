<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Package;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderPackageItem;
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
        
        // Paket yang memiliki stok gudang > 0 dan aktif
        $packages = Package::where('is_active', true)
            ->whereHas('stock', function ($query) {
                $query->where('qty', '>', 0);
            })
            ->with(['stock', 'items.product.unit'])
            ->orderBy('name')
            ->get();

        return view('sales.orders.create', compact('customers', 'products', 'packages'));
    }

    /**
     * Simpan pengajuan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'        => 'nullable|exists:customers,id',
            'catatan'            => 'nullable|string',
            'items'              => 'nullable|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'package_items'              => 'nullable|array',
            'package_items.*.package_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('packages', 'id')->whereNull('deleted_at')
            ],
            'package_items.*.qty'        => 'required|integer|min:1',
        ]);

        $hasItems = !empty($request->items);
        $hasPackages = !empty($request->package_items);

        // Validasi minimal ada 1 item
        if (!$hasItems && !$hasPackages) {
            return back()->withInput()->with('error', 'Gagal membuat pengajuan: Anda harus menambahkan minimal 1 produk satuan atau 1 paket.');
        }

        // Mencegah duplikasi produk satuan dalam satu pengajuan
        if ($hasItems) {
            $productIds = array_column($request->items, 'product_id');
            if (count($productIds) !== count(array_unique($productIds))) {
                return back()->withInput()->with('error', 'Gagal membuat pengajuan: Tidak boleh ada produk satuan duplikat.');
            }
        }

        // Mencegah duplikasi paket dalam satu pengajuan
        if ($hasPackages) {
            $packageIds = array_column($request->package_items, 'package_id');
            if (count($packageIds) !== count(array_unique($packageIds))) {
                return back()->withInput()->with('error', 'Gagal membuat pengajuan: Tidak boleh ada paket duplikat.');
            }

            // Memastikan paket yang diajukan aktif
            $activePackages = Package::whereIn('id', $packageIds)->where('is_active', true)->get()->keyBy('id');
            if (count($activePackages) !== count($packageIds)) {
                return back()->withInput()->with('error', 'Gagal membuat pengajuan: Beberapa paket yang diajukan sedang tidak aktif.');
            }
        }

        // Validasi terhadap stok produk satuan di gudang
        if ($hasItems) {
            $accumulatedQty = [];
            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $qty = (int)$item['qty'];
                if (!isset($accumulatedQty[$productId])) {
                    $accumulatedQty[$productId] = 0;
                }
                $accumulatedQty[$productId] += $qty;
            }

            foreach ($accumulatedQty as $productId => $totalQty) {
                $product = Product::find($productId);
                if (!$product) {
                    return back()->withInput()->with('error', 'Gagal membuat pengajuan: Produk tidak valid.');
                }

                if ($totalQty > $product->current_stock) {
                    $currentStockFormatted = number_format($product->current_stock, 0, ',', '.');
                    $totalQtyFormatted = number_format($totalQty, 0, ',', '.');
                    $unitName = $product->unit->name ?? 'pcs';
                    return back()->withInput()->with('error', "Stok '{$product->name}' tidak mencukupi. Tersedia: {$currentStockFormatted} {$unitName}, diajukan: {$totalQtyFormatted} {$unitName}.");
                }
            }
        }

        // Validasi terhadap stok paket di gudang
        if ($hasPackages) {
            $accumulatedPackageQty = [];
            foreach ($request->package_items as $item) {
                $packageId = $item['package_id'];
                $qty = (int)$item['qty'];
                if (!isset($accumulatedPackageQty[$packageId])) {
                    $accumulatedPackageQty[$packageId] = 0;
                }
                $accumulatedPackageQty[$packageId] += $qty;
            }

            foreach ($accumulatedPackageQty as $packageId => $totalQty) {
                $package = Package::with('stock')->find($packageId);
                if (!$package) {
                    return back()->withInput()->with('error', 'Gagal membuat pengajuan: Paket tidak valid.');
                }

                $stockQty = $package->stock ? $package->stock->qty : 0.00;
                if ($totalQty > $stockQty) {
                    $currentStockFormatted = number_format($stockQty, 0, ',', '.');
                    $totalQtyFormatted = number_format($totalQty, 0, ',', '.');
                    return back()->withInput()->with('error', "Stok paket '{$package->name}' di gudang tidak cukup. Tersedia: {$currentStockFormatted} pack, diajukan: {$totalQtyFormatted} pack.");
                }
            }
        }

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

            if ($hasItems) {
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
            }

            if ($hasPackages) {
                foreach ($request->package_items as $item) {
                    $package = Package::find($item['package_id']);
                    $subtotal = $item['qty'] * $package->selling_price;

                    SalesOrderPackageItem::create([
                        'sales_order_id' => $order->id,
                        'package_id'     => $package->id,
                        'qty'            => $item['qty'],
                        'harga'          => $package->selling_price,
                        'subtotal'       => $subtotal,
                    ]);

                    $total += $subtotal;
                }
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

        $order->load(['customer', 'items.product', 'packageItems.package.items.product']);
        return view('sales.orders.show', compact('order'));
    }
}

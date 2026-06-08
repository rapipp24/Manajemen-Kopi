<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::withCount('items');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $packages = $query->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $nextCode = Package::generateNextCode();
        return view('admin.packages.create', compact('products', 'nextCode'));
    }

    public function store(Request $request)
    {
        // Bersihkan selling_price dari format Rupiah
        if ($request->has('selling_price')) {
            $price = $request->input('selling_price');
            $cleaned = str_replace(['Rp', ' ', '.'], '', $price);
            $cleaned = str_replace(',', '.', $cleaned);
            $request->merge(['selling_price' => $cleaned !== '' ? (float)$cleaned : null]);
        }

        // Generate code otomatis jika kosong
        if (!$request->filled('code')) {
            $request->merge(['code' => Package::generateNextCode()]);
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:packages,code',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0.01',
            'is_active' => 'nullable',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ], [
            'code.required' => 'Kode paket wajib diisi.',
            'code.unique' => 'Kode paket sudah digunakan.',
            'name.required' => 'Nama paket wajib diisi.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'selling_price.min' => 'Harga jual harus lebih besar dari 0.',
            'items.required' => 'Paket minimal harus memiliki 1 komponen produk.',
            'items.min' => 'Paket minimal harus memiliki 1 komponen produk.',
            'items.*.product_id.required' => 'Produk komponen wajib dipilih.',
            'items.*.product_id.exists' => 'Produk komponen tidak valid.',
            'items.*.qty.required' => 'Qty komponen wajib diisi.',
            'items.*.qty.min' => 'Qty komponen harus lebih besar dari 0.',
        ]);

        $productIds = collect($request->input('items'))->pluck('product_id')->all();
        if (count($productIds) !== count(array_unique($productIds))) {
            return back()->withErrors(['items' => 'Produk komponen tidak boleh duplikat dalam paket yang sama.'])->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $package = Package::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'selling_price' => $request->selling_price,
                    'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                    'description' => $request->description,
                ]);

                foreach ($request->items as $item) {
                    PackageItem::create([
                        'package_id' => $package->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                    ]);
                }
            });

            return redirect()->route('admin.packages.index')
                ->with('success', 'Paket berhasil ditambahkan!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan paket: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Package $package)
    {
        $package->load('items.product.unit');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        $package->load('items');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'products'));
    }

    public function update(Request $request, Package $package)
    {
        // Bersihkan selling_price dari format Rupiah
        if ($request->has('selling_price')) {
            $price = $request->input('selling_price');
            $cleaned = str_replace(['Rp', ' ', '.'], '', $price);
            $cleaned = str_replace(',', '.', $cleaned);
            $request->merge(['selling_price' => $cleaned !== '' ? (float)$cleaned : null]);
        }

        // Generate code otomatis jika kosong
        if (!$request->filled('code')) {
            $request->merge(['code' => Package::generateNextCode()]);
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:packages,code,' . $package->id,
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0.01',
            'is_active' => 'nullable',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ], [
            'code.required' => 'Kode paket wajib diisi.',
            'code.unique' => 'Kode paket sudah digunakan.',
            'name.required' => 'Nama paket wajib diisi.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'selling_price.min' => 'Harga jual harus lebih besar dari 0.',
            'items.required' => 'Paket minimal harus memiliki 1 komponen produk.',
            'items.min' => 'Paket minimal harus memiliki 1 komponen produk.',
            'items.*.product_id.required' => 'Produk komponen wajib dipilih.',
            'items.*.product_id.exists' => 'Produk komponen tidak valid.',
            'items.*.qty.required' => 'Qty komponen wajib diisi.',
            'items.*.qty.min' => 'Qty komponen harus lebih besar dari 0.',
        ]);

        $productIds = collect($request->input('items'))->pluck('product_id')->all();
        if (count($productIds) !== count(array_unique($productIds))) {
            return back()->withErrors(['items' => 'Produk komponen tidak boleh duplikat dalam paket yang sama.'])->withInput();
        }

        try {
            DB::transaction(function () use ($request, $package) {
                $package->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'selling_price' => $request->selling_price,
                    'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
                    'description' => $request->description,
                ]);

                // Hapus komponen lama lalu buat baru
                $package->items()->delete();

                foreach ($request->items as $item) {
                    PackageItem::create([
                        'package_id' => $package->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                    ]);
                }
            });

            return redirect()->route('admin.packages.index')
                ->with('success', 'Paket berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui paket: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus!');
    }
}

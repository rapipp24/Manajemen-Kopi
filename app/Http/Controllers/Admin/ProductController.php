<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;
use App\Models\ProductCategory;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('unit')->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('admin.products.create', compact('units', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Generate Auto Code: PRD-0001
        $lastProduct = Product::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastProduct ? $lastProduct->id + 1 : 1;
        $data['code'] = 'PRD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan! (Kode: ' . $data['code'] . ')');
    }

    public function edit(Product $product)
    {
        $units = Unit::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'units', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}

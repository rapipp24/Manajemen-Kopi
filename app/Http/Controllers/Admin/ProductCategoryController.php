<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::latest()->get();
        return view('admin.product-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:50|unique:product_categories']);
        ProductCategory::create($request->all());
        return back()->with('success', 'Kategori berhasil ditambah!');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate(['name' => 'required|max:50|unique:product_categories,name,' . $productCategory->id]);
        $productCategory->update($request->all());
        return back()->with('success', 'Kategori berhasil diubah!');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Kategori masih digunakan oleh produk.');
        }
        $productCategory->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Package;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['productCategory', 'unit'])->where('is_active', true)->latest()->get();

        $packages = Package::where('is_active', true)
            ->whereHas('stock', function ($query) {
                $query->where('qty', '>', 0);
            })
            ->with(['stock', 'items.product.unit'])
            ->orderBy('name')
            ->get();

        return view('user.products', compact('products', 'packages'));
    }
}

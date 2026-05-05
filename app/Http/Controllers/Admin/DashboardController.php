<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;

use App\Models\Supplier;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'raw_material_count' => RawMaterial::count(),
            'product_count'      => Product::count(),
            'order_count'        => Order::whereMonth('created_at', now()->month)->count(),
            'total_sales'        => Sale::whereMonth('created_at', now()->month)->sum('total_amount'),
        ];

        // Ambil aktivitas terbaru (gabungan Supplier dan Unit)
        $recent_suppliers = Supplier::latest()->take(10)->get()->map(function($item) {
            return [
                'description' => "Supplier baru ditambahkan: " . $item->name,
                'time'        => $item->created_at,
                'time_human'  => $item->created_at->diffForHumans(),
                'icon'        => 'user-plus'
            ];
        });

        $recent_units = Unit::latest()->take(10)->get()->map(function($item) {
            return [
                'description' => "Satuan baru dibuat: " . $item->name . " (" . $item->code . ")",
                'time'        => $item->created_at,
                'time_human'  => $item->created_at->diffForHumans(),
                'icon'        => 'tag'
            ];
        });

        // Gabungkan dan urutkan berdasarkan waktu terbaru
        $recent_activities = $recent_suppliers->concat($recent_units)
            ->sortByDesc('time')
            ->take(10);

        return view('admin.dashboard', compact('stats', 'recent_activities'));
    }
}

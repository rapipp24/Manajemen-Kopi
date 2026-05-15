<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 0. Filter Tanggal (Default: Bulan Ini)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $stats = [
            'raw_material_count' => RawMaterial::count(),
            'product_count'      => Product::count(),
            'order_count'        => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_sales'        => Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'),
        ];

        // Hitung Tren (Bandingkan dengan periode sebelumnya dengan durasi yang sama)
        $diffInDays = $startDate->diffInDays($endDate) + 1;
        $prevStartDate = (clone $startDate)->subDays($diffInDays);
        $prevEndDate = (clone $startDate)->subDay();
        
        $prevSales = Sale::whereBetween('created_at', [$prevStartDate, $prevEndDate])->sum('total_amount');
        $currentSales = $stats['total_sales'];
        
        $salesTrend = 0;
        if ($prevSales > 0) {
            $salesTrend = (($currentSales - $prevSales) / $prevSales) * 100;
        } elseif ($currentSales > 0) {
            $salesTrend = 100; // Jika sebelumnya 0 dan sekarang ada penjualan, anggap naik 100%
        }
        
        $stats['sales_trend'] = $salesTrend;
        $stats['prev_sales'] = $prevSales;

        // Chart Data (Sales per Day)
        $salesData = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        
        // Fill missing days with 0 if needed, or just use what we have
        foreach ($salesData as $data) {
            $chartLabels[] = Carbon::parse($data->date)->format('d M');
            $chartValues[] = (int) $data->total;
        }

        // 1. Stok Bahan Baku Kritis (Di bawah stok minimal)
        $low_stock_materials = RawMaterial::with('unit')
            ->whereRaw('current_stock <= minimum_stock')
            ->where('is_active', true)
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 2. Stok Produk Habis (0)
        $out_of_stock_products = Product::with('unit')
            ->where('current_stock', '<=', 0)
            ->where('is_active', true)
            ->take(5)
            ->get();

        // 3. Pesanan Baru (Status Pending/Waiting)
        $latest_orders = Order::with('user')
            ->whereIn('status', ['pending', 'waiting', 'process'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Penjualan Hari Ini
        $today_sales = Sale::whereDate('created_at', now()->today())->sum('total_amount');
        $today_count = Sale::whereDate('created_at', now()->today())->count();

        return view('admin.dashboard', compact(
            'stats', 
            'low_stock_materials', 
            'out_of_stock_products', 
            'latest_orders',
            'today_sales',
            'today_count',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate'
        ));
    }
}

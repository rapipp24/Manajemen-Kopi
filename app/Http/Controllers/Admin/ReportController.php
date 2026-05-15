<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        // 1. Ambil semua item penjualan dalam periode tersebut beserta produknya
        $saleItems = SaleItem::with('product')
            ->whereHas('sale', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select('sale_items.*', 'sales.created_at as sale_date')
            ->get();

        // 2. Hitung Total Keseluruhan
        $totalGrossSales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalHpp = 0;
        
        // 3. Kelompokkan Data Harian untuk Chart
        $dailyAggregated = [];
        
        foreach ($saleItems as $item) {
            $hpp = ($item->product->cost_price ?? 0) * $item->qty;
            $totalHpp += $hpp;
            
            $date = Carbon::parse($item->sale_date)->format('Y-m-d');
            if (!isset($dailyAggregated[$date])) {
                $dailyAggregated[$date] = [
                    'sales' => 0,
                    'profit' => 0
                ];
            }
            // Catatan: total_sales harian kita ambil dari query Sales terpisah agar lebih akurat dengan diskon/pajak jika ada di level Sale
        }

        // Ambil penjualan harian secara akurat
        $salesPerDay = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('total_sales', 'date');

        $chartLabels = [];
        $chartSales = [];
        $chartProfit = [];

        // Hitung profit harian berdasarkan HPP yang sudah kita iterasi
        $dailyHpp = [];
        foreach ($saleItems as $item) {
            $date = Carbon::parse($item->sale_date)->format('Y-m-d');
            $hpp = ($item->product->cost_price ?? 0) * $item->qty;
            $dailyHpp[$date] = ($dailyHpp[$date] ?? 0) + $hpp;
        }

        // Urutkan tanggal agar chart berurutan
        $dates = $salesPerDay->keys()->sort();

        foreach ($dates as $date) {
            $sales = $salesPerDay[$date];
            $hpp = $dailyHpp[$date] ?? 0;
            
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartSales[] = (int) $sales;
            $chartProfit[] = (int) ($sales - $hpp);
        }

        $labaKotor = $totalGrossSales - $totalHpp;

        // 4. Top 5 Produk Terlaris
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('sale', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->with('product')
            ->get();

        return view('admin.reports', compact(
            'totalGrossSales',
            'totalHpp',
            'labaKotor',
            'chartLabels',
            'chartSales',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
}

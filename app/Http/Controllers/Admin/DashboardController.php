<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\SalePayment;
use App\Models\SalesDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 0. Filter Tanggal (Default: Bulan Ini)
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        // 1. Current Period: Total Uang Masuk
        $currentAdminPayments = SalePayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        $currentSalesDeposits = SalesDeposit::where('status', 'disetujui')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
        $currentCashIn = $currentAdminPayments + $currentSalesDeposits;

        $stats = [
            'raw_material_count' => RawMaterial::count(),
            'product_count'      => Product::count(),
            'order_count'        => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_sales'        => $currentCashIn, // mapping total_sales to currentCashIn to prevent view error
        ];

        // 2. Previous Period: Total Uang Masuk (for trend comparison)
        $diffInDays = $startDate->diffInDays($endDate) + 1;
        $prevStartDate = (clone $startDate)->subDays($diffInDays)->startOfDay();
        $prevEndDate = (clone $startDate)->subDay()->endOfDay();

        $prevAdminPayments = SalePayment::whereBetween('payment_date', [$prevStartDate, $prevEndDate])->sum('amount');
        $prevSalesDeposits = SalesDeposit::where('status', 'disetujui')
            ->whereBetween('payment_date', [$prevStartDate, $prevEndDate])
            ->sum('amount');
        $prevCashIn = $prevAdminPayments + $prevSalesDeposits;

        $salesTrend = 0;
        if ($prevCashIn > 0) {
            $salesTrend = (($currentCashIn - $prevCashIn) / $prevCashIn) * 100;
        } elseif ($currentCashIn > 0) {
            $salesTrend = 100;
        }

        $stats['sales_trend'] = $salesTrend;
        $stats['prev_sales'] = $prevCashIn;

        // 3. Chart Data (Tren Kas Masuk Harian)
        $dailyAdminPayments = SalePayment::select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total_amount', 'date');

        $dailySalesDeposits = SalesDeposit::select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where('status', 'disetujui')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total_amount', 'date');

        $allDates = collect(array_merge($dailyAdminPayments->keys()->all(), $dailySalesDeposits->keys()->all()))
            ->unique()
            ->sort();

        $chartLabels = [];
        $chartValues = [];

        foreach ($allDates as $dateStr) {
            $adminVal = (float)($dailyAdminPayments[$dateStr] ?? 0.0);
            $salesVal = (float)($dailySalesDeposits[$dateStr] ?? 0.0);
            $totalVal = $adminVal + $salesVal;
            
            $chartLabels[] = Carbon::parse($dateStr)->format('d M');
            $chartValues[] = (int)$totalVal;
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

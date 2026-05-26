<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\ProductionBatch;
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

        // Count bahan baku hampir habis untuk info helper
        $criticalMaterialsCount = RawMaterial::whereRaw('current_stock <= minimum_stock')
            ->where('is_active', true)
            ->count();

        // Total volume fisik produk jadi (pcs/pack) untuk info helper
        $totalProductStock = Product::where('is_active', true)
            ->sum('current_stock');

        $stats = [
            'raw_material_count'       => RawMaterial::count(),
            'product_count'            => Product::count(),
            'order_count'              => SalesOrder::whereBetween('created_at', [$startDate, $endDate])->count(), // Menggunakan SalesOrder
            'total_sales'              => $currentCashIn, 
            'critical_materials_count' => $criticalMaterialsCount,
            'total_product_stock'      => $totalProductStock,
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

        // 4. Stok Bahan Baku Kritis (Di bawah stok minimal)
        $low_stock_materials = RawMaterial::with('unit')
            ->whereRaw('current_stock <= minimum_stock')
            ->where('is_active', true)
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 5. Stok Produk Habis (0)
        $out_of_stock_products = Product::with('unit')
            ->where('current_stock', '<=', 0)
            ->where('is_active', true)
            ->take(5)
            ->get();

        // 6. Pengajuan Barang Sales Baru (Status 'menunggu')
        $latest_orders = SalesOrder::with(['sales', 'customer'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // 7. Ringkasan Harian (Today Summary)
        // Produksi Hari Ini dari ProductionBatch
        $today_production_count = ProductionBatch::whereDate('production_date', Carbon::today())->count();
        $today_production_output = ProductionBatch::whereDate('production_date', Carbon::today())->sum('total_output');

        // Total Kas Masuk Hari Ini = SalePayment Hari Ini + SalesDeposit Disetujui Hari Ini
        $todayAdminPayments = SalePayment::whereDate('payment_date', Carbon::today())->sum('amount');
        $todaySalesDeposits = SalesDeposit::where('status', 'disetujui')
            ->whereDate('payment_date', Carbon::today())
            ->sum('amount');
        $today_cash_in = $todayAdminPayments + $todaySalesDeposits;

        return view('admin.dashboard', compact(
            'stats', 
            'low_stock_materials', 
            'out_of_stock_products', 
            'latest_orders',
            'today_production_count',
            'today_production_output',
            'today_cash_in',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate'
        ));
    }
}

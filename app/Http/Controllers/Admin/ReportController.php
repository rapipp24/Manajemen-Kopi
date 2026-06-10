<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\SalesDeposit;
use App\Models\RawMaterialReceipt;
use App\Models\DeliveryReport;
use App\Models\SalesReturnItem;
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

        // 1. Uang Masuk dari Penjualan Admin Langsung
        $totalAdminPayments = SalePayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');

        // 2. Uang Masuk dari Setoran Sales Lapangan yang Disetujui
        $totalSalesDeposits = SalesDeposit::where('status', 'disetujui')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // Total Uang Masuk
        $totalCashIn = $totalAdminPayments + $totalSalesDeposits;

        // 3. Uang Keluar dari Pembelian Bahan Baku
        $totalRawMaterialPurchases = RawMaterialReceipt::whereBetween('receipt_date', [$startDate, $endDate])->sum('total_amount');

        // Total Uang Keluar Tercatat
        $totalCashOut = $totalRawMaterialPurchases;

        // 4. Profit Tercatat
        $profitTercatat = $totalCashIn - $totalCashOut;

        // 5. Rekap Piutang dari Delivery Reports (dalam periode terpilih)
        $deliveryReports = DeliveryReport::with(['sales', 'customer', 'items.product', 'packageItems'])
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->get();

        // N+1 Avoidance: Query total return yang sudah diterima untuk seluruh DR terpilih
        $returnsPerReport = [];
        if ($deliveryReports->isNotEmpty()) {
            $returnsPerReport = SalesReturnItem::join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')
                ->where('sales_returns.status', 'diterima')
                ->where('sales_returns.return_type', 'potong_tagihan')
                ->whereIn('sales_returns.delivery_report_id', $deliveryReports->pluck('id'))
                ->groupBy('sales_returns.delivery_report_id')
                ->select('sales_returns.delivery_report_id', DB::raw('SUM(sales_return_items.subtotal_return) as total_return'))
                ->pluck('total_return', 'sales_returns.delivery_report_id')
                ->all();
        }

        $totalDeliveryAmount = 0.0;
        $totalReturnDiterima = 0.0;
        $totalTagihanEfektif = 0.0;
        $totalPaidToko = 0.0;
        $totalSisaPiutang = 0.0;
        $totalKelebihanBayar = 0.0;
        $totalKelebihanBayarTercatat = 0.0;
        $totalKelebihanBayarBelumSelesai = 0.0;

        $salesBreakdown = [];
        $tokoBreakdown = [];
        $tagihanBelumBayar = [];
        $bayarLebihBelumSelesai = [];
        $today = now()->startOfDay();

        foreach ($deliveryReports as $report) {
            $returnVal = (float)($returnsPerReport[$report->id] ?? 0.0);
            $tagihanEfektif = max(0.0, (float)$report->total_amount - $returnVal);
            $paidVal = (float)$report->down_payment_amount;
            $sisaPiutang = $tagihanEfektif - $paidVal;

            $totalDeliveryAmount += (float)$report->total_amount;
            $totalReturnDiterima += $returnVal;
            $totalTagihanEfektif += $tagihanEfektif;
            $totalPaidToko += $paidVal;

            if ($sisaPiutang > 0) {
                $totalSisaPiutang += $sisaPiutang;
            } else {
                $overpaymentVal = abs($sisaPiutang);
                $totalKelebihanBayar += $overpaymentVal;
                $totalKelebihanBayarTercatat += $overpaymentVal;
                if (is_null($report->overpayment_resolved_at)) {
                    $totalKelebihanBayarBelumSelesai += $overpaymentVal;
                }
            }

            // A. Breakdown Per Sales
            $salesId = $report->sales_id;
            if ($salesId) {
                $salesName = $report->sales->name ?? 'Sales Tidak Dikenal';
                if (!isset($salesBreakdown[$salesId])) {
                    $salesBreakdown[$salesId] = [
                        'name' => $salesName,
                        'tagihan_efektif' => 0.0,
                        'total_paid' => 0.0,
                        'total_return' => 0.0,
                        'sisa_piutang' => 0.0,
                        'kelebihan_bayar' => 0.0,
                    ];
                }
                $salesBreakdown[$salesId]['tagihan_efektif'] += $tagihanEfektif;
                $salesBreakdown[$salesId]['total_paid'] += $paidVal;
                $salesBreakdown[$salesId]['total_return'] += $returnVal;
                if ($sisaPiutang > 0) {
                    $salesBreakdown[$salesId]['sisa_piutang'] += $sisaPiutang;
                } else {
                    $salesBreakdown[$salesId]['kelebihan_bayar'] += abs($sisaPiutang);
                }
            }

            // B. Breakdown Per Toko (Customer)
            $status = 'belum_bayar';
            if ($sisaPiutang < 0) {
                $status = 'kelebihan_bayar';
            } elseif ($sisaPiutang == 0) {
                $status = 'lunas';
            } else {
                if ($report->due_date && Carbon::parse($report->due_date)->startOfDay()->lt($today)) {
                    $status = 'lewat_tempo';
                } elseif ($paidVal > 0) {
                    $status = 'dp';
                } else {
                    $status = 'belum_bayar';
                }
            }

            $tokoBreakdown[] = [
                'toko_name' => $report->toko_name,
                'sales_name' => $report->sales->name ?? '—',
                'tagihan_efektif' => $tagihanEfektif,
                'total_paid' => $paidVal,
                'sisa_piutang' => $sisaPiutang,
                'due_date' => $report->due_date ? Carbon::parse($report->due_date)->format('d-m-Y') : '—',
                'status' => $status,
            ];

            // Section 1: Tagihan Toko Belum Dibayar
            if ($sisaPiutang > 0) {
                $tagihanBelumBayar[] = [
                    'id' => $report->id,
                    'toko_name' => $report->toko_name,
                    'sales_name' => $report->sales->name ?? '—',
                    'delivery_date' => $report->delivery_date ? Carbon::parse($report->delivery_date)->format('d-m-Y') : '—',
                    'due_date' => $report->due_date ? Carbon::parse($report->due_date)->format('d-m-Y') : '—',
                    'tagihan_efektif' => $tagihanEfektif,
                    'total_paid' => $paidVal,
                    'sisa_tagihan' => $sisaPiutang,
                    'lewat_tempo' => $report->due_date && Carbon::parse($report->due_date)->startOfDay()->lt($today),
                ];
            }

            // Section 2: Bayar Lebih Belum Diselesaikan
            if ($sisaPiutang < 0 && is_null($report->overpayment_resolved_at)) {
                $bayarLebihBelumSelesai[] = [
                    'id' => $report->id,
                    'toko_name' => $report->toko_name,
                    'sales_name' => $report->sales->name ?? '—',
                    'delivery_date' => $report->delivery_date ? Carbon::parse($report->delivery_date)->format('d-m-Y') : '—',
                    'tagihan_efektif' => $tagihanEfektif,
                    'total_paid' => $paidVal,
                    'bayar_lebih' => abs($sisaPiutang),
                ];
            }
        }

        // 6. Tren Kas Masuk Harian (ApexCharts)
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
        $chartSales = [];

        foreach ($allDates as $dateStr) {
            $adminVal = (float)($dailyAdminPayments[$dateStr] ?? 0.0);
            $salesVal = (float)($dailySalesDeposits[$dateStr] ?? 0.0);
            $totalVal = $adminVal + $salesVal;
            
            $chartLabels[] = Carbon::parse($dateStr)->format('d M');
            $chartSales[] = (int)$totalVal;
        }

        // 7. Data Penjualan Admin Langsung (Retail/Gudang)
        $totalAdminGrossSales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        
        $saleItems = SaleItem::with('product')
            ->whereHas('sale', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        $totalAdminHpp = 0.0;
        foreach ($saleItems as $item) {
            $totalAdminHpp += (float)($item->product->cost_price ?? 0.0) * (float)$item->qty;
        }

        // HPP dari Delivery Reports dalam periode
        $totalDeliveryHpp = 0.0;
        foreach ($deliveryReports as $report) {
            foreach ($report->items as $item) {
                $totalDeliveryHpp += (float)($item->product->cost_price ?? 0.0) * (float)$item->qty;
            }
            foreach ($report->packageItems as $pkgItem) {
                $totalDeliveryHpp += (float)($pkgItem->package_hpp_snapshot ?? 0.0) * (float)$pkgItem->qty;
            }
        }

        // HPP dari Return yang sudah Diterima untuk Delivery Reports terpilih
        $totalReturnHpp = 0.0;
        if ($deliveryReports->isNotEmpty()) {
            $returnItems = SalesReturnItem::join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')
                ->where('sales_returns.status', 'diterima')
                ->where('sales_returns.return_type', 'potong_tagihan')
                ->whereIn('sales_returns.delivery_report_id', $deliveryReports->pluck('id'))
                ->with('product')
                ->get(['sales_return_items.*']);

            foreach ($returnItems as $item) {
                $totalReturnHpp += (float)($item->product->cost_price ?? 0.0) * (float)$item->qty_return;
            }
        }

        // Rumus Laba / Margin: (Nilai Penjualan - HPP)
        $nilaiPenjualan = $totalAdminGrossSales + $totalDeliveryAmount - $totalReturnDiterima;
        $totalHppPenjualan = $totalAdminHpp + $totalDeliveryHpp - $totalReturnHpp;
        $labaMargin = $nilaiPenjualan - $totalHppPenjualan;

        $totalNilaiPenjualan = $nilaiPenjualan;
        $totalHppProduk = $totalHppPenjualan;

        // Selisih Tercatat = Total Uang Masuk - Total Uang Keluar Tercatat
        $selisihTercatat = $totalCashIn - $totalCashOut;

        // Top 5 Produk Terlaris (dari Penjualan Admin Langsung)
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
            'startDate',
            'endDate',
            'totalAdminPayments',
            'totalSalesDeposits',
            'totalCashIn',
            'totalRawMaterialPurchases',
            'totalCashOut',
            'profitTercatat',
            'selisihTercatat',
            'labaMargin',
            'totalNilaiPenjualan',
            'totalHppProduk',
            'totalDeliveryAmount',
            'totalReturnDiterima',
            'totalTagihanEfektif',
            'totalPaidToko',
            'totalSisaPiutang',
            'totalKelebihanBayar',
            'totalKelebihanBayarTercatat',
            'totalKelebihanBayarBelumSelesai',
            'salesBreakdown',
            'tokoBreakdown',
            'tagihanBelumBayar',
            'bayarLebihBelumSelesai',
            'chartLabels',
            'chartSales',
            'totalAdminGrossSales',
            'totalAdminHpp',
            'topProducts'
        ));
    }
}

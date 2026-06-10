<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryReport;
use App\Models\User;

class DeliveryReportController extends Controller
{
    /**
     * Admin melihat semua laporan pengiriman dari semua sales
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $activeTab = $request->input('tab', 'delivery');
        $salesUsers = User::where('role', 'sales')->orderBy('name')->get();
        $products = \App\Models\Product::orderBy('name')->get();

        $reports = null;
        $salesStocks = null;
        $deliveredItems = null;
        $salesStockSummary = null;

        if ($activeTab === 'stock') {
            // 1. Ringkasan Stok per Sales
            $salesStockSummary = \App\Models\SalesStock::select('user_id', \Illuminate\Support\Facades\DB::raw('SUM(qty) as total_qty'))
                ->with('user')
                ->groupBy('user_id')
                ->orderBy('total_qty', 'desc')
                ->get();

            // 2. Query Detail Stok
            $stockQuery = \App\Models\SalesStock::with(['user', 'product.unit']);
            if ($request->filled('sales_id')) {
                $stockQuery->where('user_id', $request->sales_id);
            }
            if ($request->filled('product_id')) {
                $stockQuery->where('product_id', $request->product_id);
            }
            if ($request->filled('stock_status')) {
                if ($request->stock_status === 'available') {
                    $stockQuery->where('qty', '>', 0);
                } elseif ($request->stock_status === 'empty') {
                    $stockQuery->where('qty', '<=', 0);
                }
            }
            if ($request->input('sort_stock') === 'asc') {
                $stockQuery->orderBy('qty', 'asc');
            } else {
                $stockQuery->orderBy('qty', 'desc');
            }
            $salesStocks = $stockQuery->paginate(20)->withQueryString();
        } elseif ($activeTab === 'delivered') {
            // 3. Query Barang Terkirim ke Toko (berbasis DeliveryReportItem)
            $deliveredQuery = \App\Models\DeliveryReportItem::with([
                'deliveryReport.sales',
                'deliveryReport.customer',
                'product.unit',
                'salesReturnItems.salesReturn'
            ]);

            if ($request->filled('sales_id') || $request->filled('date')) {
                $deliveredQuery->whereHas('deliveryReport', function ($q) use ($request) {
                    if ($request->filled('sales_id')) {
                        $q->where('sales_id', $request->sales_id);
                    }
                    if ($request->filled('date')) {
                        $q->whereDate('delivery_date', $request->date);
                    }
                });
            }

            if ($request->filled('product_id')) {
                $deliveredQuery->where('product_id', $request->product_id);
            }

            $deliveredItems = $deliveredQuery->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();
        } else {
            // Default Tab: Laporan Pengiriman
            $query = DeliveryReport::with(['sales', 'customer', 'items']);

            if ($request->filled('sales_id')) {
                $query->where('sales_id', $request->sales_id);
            }
            
            if ($request->filled('date')) {
                $query->whereDate('delivery_date', $request->date);
            }

            $reports = $query->orderBy('delivery_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.delivery-reports.index', compact(
            'reports',
            'salesUsers',
            'products',
            'activeTab',
            'salesStocks',
            'deliveredItems',
            'salesStockSummary'
        ));
    }

    /**
     * Admin melihat detail laporan pengiriman tertentu
     */
    public function show(DeliveryReport $deliveryReport)
    {
        $deliveryReport->load(['sales', 'customer', 'items.product.unit', 'packageItems.package.items.product', 'creator', 'overpaymentResolver']);
        return view('admin.delivery-reports.show', compact('deliveryReport'));
    }

    /**
     * Admin menandai bayar lebih pada laporan pengiriman sudah diselesaikan.
     */
    public function resolveOverpayment(\Illuminate\Http\Request $request, DeliveryReport $deliveryReport)
    {
        $request->validate([
            'overpayment_resolution_note' => 'required|string|max:1000',
        ], [
            'overpayment_resolution_note.required' => 'Catatan penyelesaian wajib diisi.',
        ]);

        if (!$deliveryReport->is_overpaid) {
            return back()->with('error', 'Laporan pengiriman ini tidak memiliki kondisi Bayar Lebih.');
        }

        if ($deliveryReport->overpayment_resolved_at) {
            return back()->with('error', 'Kondisi Bayar Lebih pada laporan ini sudah diselesaikan sebelumnya.');
        }

        $deliveryReport->update([
            'overpayment_resolved_at' => now(),
            'overpayment_resolved_by' => \Illuminate\Support\Facades\Auth::id(),
            'overpayment_resolution_note' => $request->overpayment_resolution_note,
        ]);

        return back()->with('success', 'Kondisi Bayar Lebih berhasil ditandai sebagai Sudah Diselesaikan.');
    }
}

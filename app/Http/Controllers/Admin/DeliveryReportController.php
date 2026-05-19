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

        $salesUsers = User::where('role', 'sales')->orderBy('name')->get();

        return view('admin.delivery-reports.index', compact('reports', 'salesUsers'));
    }

    /**
     * Admin melihat detail laporan pengiriman tertentu
     */
    public function show(DeliveryReport $deliveryReport)
    {
        $deliveryReport->load(['sales', 'customer', 'items.product.unit', 'creator']);
        return view('admin.delivery-reports.show', compact('deliveryReport'));
    }
}

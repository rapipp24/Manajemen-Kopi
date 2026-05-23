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
        $deliveryReport->load(['sales', 'customer', 'items.product.unit', 'creator', 'overpaymentResolver']);
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

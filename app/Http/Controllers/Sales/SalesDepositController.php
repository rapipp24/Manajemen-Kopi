<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\DeliveryReport;
use App\Models\SalesDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SalesDepositController extends Controller
{
    public function index()
    {
        $deposits = SalesDeposit::with(['deliveryReport'])
            ->where('sales_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sales.deposits.index', compact('deposits'));
    }

    public function create(Request $request)
    {
        // Get all delivery reports of this sales that are not fully paid
        // total_amount > down_payment_amount
        $reports = DeliveryReport::where('sales_id', Auth::id())
            ->get()
            ->filter(function ($report) {
                return $report->remaining_amount > 0;
            });

        $selectedReportId = $request->input('delivery_report_id');

        return view('sales.deposits.create', compact('reports', 'selectedReportId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_report_id' => 'required|exists:delivery_reports,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $report = DeliveryReport::where('id', $request->delivery_report_id)
            ->where('sales_id', Auth::id())
            ->firstOrFail();

        if ($report->remaining_amount <= 0) {
            return back()->withInput()->with('error', 'Laporan pengiriman ini sudah lunas.');
        }

        if ($request->amount > $report->remaining_amount) {
            return back()->withInput()->with('error', 'Nominal setoran melebihi sisa tagihan (Maksimal Rp ' . number_format($report->remaining_amount, 0, ',', '.') . ').');
        }

        $depositNumber = 'DEP-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        SalesDeposit::create([
            'deposit_number' => $depositNumber,
            'delivery_report_id' => $report->id,
            'sales_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('sales.deposits.index')
            ->with('success', 'Setoran berhasil diajukan dan menunggu verifikasi admin.');
    }

    public function show(SalesDeposit $deposit)
    {
        if ($deposit->sales_id !== Auth::id()) {
            abort(403);
        }

        $deposit->load(['deliveryReport', 'verifier']);
        return view('sales.deposits.show', compact('deposit'));
    }
}

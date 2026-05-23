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
        // and do not have any pending deposits (status: menunggu_verifikasi)
        $reports = DeliveryReport::where('sales_id', Auth::id())
            ->whereDoesntHave('deposits', function ($query) {
                $query->where('status', 'menunggu_verifikasi');
            })
            ->get()
            ->filter(function ($report) {
                return $report->remaining_amount > 0;
            });

        $selectedReportId = $request->input('delivery_report_id');

        return view('sales.deposits.create', compact('reports', 'selectedReportId'));
    }

    public function store(Request $request)
    {
        $isTransfer = $request->payment_method === 'Transfer';

        $request->validate([
            'delivery_report_id' => 'required|exists:delivery_reports,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:Tunai,Transfer',
            'note' => 'nullable|string',
            'payment_proof' => ($isTransfer ? 'required' : 'nullable') . '|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        $report = DeliveryReport::where('id', $request->delivery_report_id)
            ->where('sales_id', Auth::id())
            ->firstOrFail();

        // Validasi backend: Cegah double submit jika masih ada setoran berstatus menunggu_verifikasi untuk laporan ini
        $hasPendingDeposit = SalesDeposit::where('delivery_report_id', $report->id)
            ->where('status', 'menunggu_verifikasi')
            ->exists();

        if ($hasPendingDeposit) {
            return back()->withInput()->with('error', 'Masih ada setoran untuk laporan ini yang menunggu verifikasi admin.');
        }

        if ($report->remaining_amount <= 0) {
            return back()->withInput()->with('error', 'Laporan pengiriman ini sudah lunas.');
        }

        if ($request->amount > $report->remaining_amount) {
            return back()->withInput()->with('error', 'Nominal setoran melebihi sisa tagihan (Maksimal Rp ' . number_format($report->remaining_amount, 0, ',', '.') . ').');
        }

        // Simpan file bukti transfer hanya jika seluruh validasi bisnis di atas telah lolos (mencegah file yatim/orphan)
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs/sales-deposits', 'public');
        }

        $depositNumber = 'DEP-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        SalesDeposit::create([
            'deposit_number' => $depositNumber,
            'delivery_report_id' => $report->id,
            'sales_id' => Auth::id(),
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_proof_path' => $paymentProofPath,
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

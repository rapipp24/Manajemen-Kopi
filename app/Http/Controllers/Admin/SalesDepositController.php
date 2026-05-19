<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesDeposit;
use App\Models\DeliveryReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesDepositController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesDeposit::with(['sales', 'deliveryReport']);

        if ($request->filled('sales_id')) {
            $query->where('sales_id', $request->sales_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $salesUsers = User::where('role', 'sales')->orderBy('name')->get();

        return view('admin.deposits.index', compact('deposits', 'salesUsers'));
    }

    public function show(SalesDeposit $deposit)
    {
        $deposit->load(['sales', 'deliveryReport.customer', 'verifier']);
        return view('admin.deposits.show', compact('deposit'));
    }

    public function approve(SalesDeposit $deposit)
    {
        DB::beginTransaction();
        try {
            // Lock the record for update to prevent double clicking/race conditions
            $deposit = SalesDeposit::where('id', $deposit->id)->lockForUpdate()->firstOrFail();

            if ($deposit->status !== 'menunggu_verifikasi') {
                throw new Exception('Status setoran sudah tidak menunggu verifikasi.');
            }

            $report = DeliveryReport::where('id', $deposit->delivery_report_id)->lockForUpdate()->firstOrFail();

            if ($deposit->amount > $report->remaining_amount) {
                throw new Exception('Nominal setoran melebihi sisa tagihan laporan pengiriman ini.');
            }

            // Update status setoran
            $deposit->update([
                'status' => 'disetujui',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Update delivery report
            // Tambahkan amount ke down_payment_amount (yang dianggap cache total uang masuk)
            $newPaidAmount = $report->down_payment_amount + $deposit->amount;
            
            $paymentStatus = 'belum_bayar';
            if ($newPaidAmount >= $report->total_amount) {
                $paymentStatus = 'lunas';
            } elseif ($newPaidAmount > 0) {
                $paymentStatus = 'dp';
            }

            $report->update([
                'down_payment_amount' => $newPaidAmount,
                'payment_status' => $paymentStatus,
            ]);

            DB::commit();
            return back()->with('success', 'Setoran berhasil disetujui.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi setoran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, SalesDeposit $deposit)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $deposit = SalesDeposit::where('id', $deposit->id)->lockForUpdate()->firstOrFail();

            if ($deposit->status !== 'menunggu_verifikasi') {
                throw new Exception('Status setoran sudah tidak menunggu verifikasi.');
            }

            // Update status setoran
            $deposit->update([
                'status' => 'ditolak',
                'rejection_reason' => $request->rejection_reason,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Setoran berhasil ditolak.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak setoran: ' . $e->getMessage());
        }
    }
}

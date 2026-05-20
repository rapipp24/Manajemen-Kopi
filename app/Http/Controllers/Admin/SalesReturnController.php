<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesReturnController extends Controller
{
    /**
     * Daftar semua return dari semua sales.
     * Admin bisa filter berdasarkan status.
     */
    public function index(Request $request)
    {
        $query = SalesReturn::with(['sales', 'deliveryReport']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sales_id')) {
            $query->where('sales_id', $request->sales_id);
        }

        $returns = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $salesUsers = User::where('role', 'sales')->orderBy('name')->get();

        return view('admin.returns.index', compact('returns', 'salesUsers'));
    }

    /**
     * Detail return untuk diverifikasi admin.
     */
    public function show(SalesReturn $return)
    {
        $return->load(['sales', 'deliveryReport.items.product', 'items.product', 'approver']);

        return view('admin.returns.show', compact('return'));
    }

    /**
     * Admin menerima return:
     * - Stok gudang bertambah
     * - StockMovement IN dibuat (user_id = null = gudang)
     * - Status menjadi 'diterima'
     * - Semua dilakukan dalam database transaction dengan locking
     */
    public function receive(SalesReturn $return)
    {
        DB::beginTransaction();
        try {
            // Lock record return agar tidak bisa diproses ganda (race condition)
            $return = SalesReturn::lockForUpdate()->findOrFail($return->id);

            if ($return->status !== 'menunggu') {
                throw new Exception('Return ini sudah diproses sebelumnya (status: ' . $return->status . ').');
            }

            $return->load('items');

            // Proses setiap item: tambah stok gudang + buat stock movement
            foreach ($return->items as $item) {
                // Lock produk agar tidak ada race condition stok
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                $stockBefore = $product->current_stock;
                $product->current_stock += $item->qty_return;
                $product->save();

                // Catat stock movement IN ke gudang (user_id = null berarti gudang)
                StockMovement::create([
                    'item_type'      => Product::class,
                    'item_id'        => $product->id,
                    'movement_type'  => 'IN',
                    'reference_type' => SalesReturn::class,
                    'reference_id'   => $return->id,
                    'qty'            => $item->qty_return,
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $product->current_stock,
                    'note'           => 'Return dari toko (Laporan: ' . $return->deliveryReport->report_number . ')',
                    'user_id'        => null, // null = stok gudang
                ]);
            }

            // Update status return
            $return->update([
                'status'      => 'diterima',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.returns.show', $return)
                ->with('success', 'Return diterima. Stok gudang berhasil ditambahkan.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses return: ' . $e->getMessage());
        }
    }

    /**
     * Admin menolak return:
     * - Stok tidak berubah
     * - StockMovement tidak dibuat
     * - Status menjadi 'ditolak'
     * - Alasan penolakan wajib dicatat
     */
    public function reject(Request $request, SalesReturn $return)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Lock agar tidak ada proses ganda
            $return = SalesReturn::lockForUpdate()->findOrFail($return->id);

            if ($return->status !== 'menunggu') {
                throw new Exception('Return ini sudah diproses sebelumnya (status: ' . $return->status . ').');
            }

            $return->update([
                'status'           => 'ditolak',
                'rejection_reason' => $request->rejection_reason,
                'approved_by'      => Auth::id(),
                'approved_at'      => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.returns.show', $return)
                ->with('success', 'Return telah ditolak. Stok dan tagihan tidak berubah.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak return: ' . $e->getMessage());
        }
    }
}

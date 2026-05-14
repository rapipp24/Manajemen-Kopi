<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionBatch;
use App\Models\ProductionBatchItem;
use App\Models\RawMaterial;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductionController extends Controller
{
    /**
     * Daftar semua produksi (dengan pagination, eager loading).
     */
    public function index()
    {
        $productions = ProductionBatch::with('creator')
            ->orderBy('production_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.productions.index', compact('productions'));
    }

    /**
     * Form tambah produksi baru.
     */
    public function create()
    {
        $materials = RawMaterial::where('is_active', true)
            ->with('unit')
            ->orderBy('name')
            ->get();

        return view('admin.productions.create', compact('materials'));
    }

    /**
     * Simpan produksi baru ke database.
     */
    public function store(Request $request)
    {
        // ── 1. Validasi input form ──────────────────────────────────────────
        $request->validate([
            'production_date'    => 'required|date',
            'product_type'       => 'required|string|max:255',
            'note'               => 'nullable|string',
            'total_output'       => 'required|numeric|min:0.01',
            'items'              => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.qty_used'        => 'required|numeric|min:0.01',
        ]);

        // ── 2. Validasi stok mencukupi sebelum transaksi ────────────────────
        // Kita akumulasi dulu kebutuhan per bahan (jika bahan sama muncul 2x)
        $needed = [];
        foreach ($request->items as $item) {
            $id = $item['raw_material_id'];
            $needed[$id] = ($needed[$id] ?? 0) + $item['qty_used'];
        }

        foreach ($needed as $materialId => $totalQty) {
            $material = RawMaterial::find($materialId);
            if ($material->current_stock < $totalQty) {
                return back()->withInput()->with(
                    'error',
                    "Stok {$material->name} tidak mencukupi. " .
                    "Dibutuhkan: {$totalQty}, tersedia: {$material->current_stock} {$material->unit->name}."
                );
            }
        }

        // ── 3. Mulai database transaction ────────────────────────────────────
        DB::beginTransaction();

        try {
            // 3a. Generate nomor batch: PRD-YYYYMMDD-XXX
            $date  = date('Ymd');
            $count = ProductionBatch::whereDate('created_at', today())->count() + 1;
            $batchNumber = 'PRD-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // 3b. Hitung total bahan yang digunakan
            $totalMaterialUsed = collect($request->items)->sum('qty_used');

            // 3c. Hitung susut
            $shrinkage = $totalMaterialUsed - $request->total_output;

            // 3d. Buat header produksi
            $production = ProductionBatch::create([
                'batch_number'        => $batchNumber,
                'production_date'     => $request->production_date,
                'product_type'        => $request->product_type,
                'total_material_used' => $totalMaterialUsed,
                'total_output'        => $request->total_output,
                'shrinkage'           => $shrinkage,
                'note'                => $request->note,
                'created_by'          => Auth::id(),
            ]);

            // 3e. Loop setiap bahan: simpan item, kurangi stok, catat movement
            foreach ($request->items as $item) {
                // Simpan item bahan
                ProductionBatchItem::create([
                    'production_batch_id' => $production->id,
                    'raw_material_id'     => $item['raw_material_id'],
                    'qty_used'            => $item['qty_used'],
                ]);

                // Kurangi stok bahan baku (lock row agar aman dari race condition)
                $material   = RawMaterial::lockForUpdate()->find($item['raw_material_id']);
                $stockBefore = $material->current_stock;
                $material->current_stock -= $item['qty_used'];
                $material->save();

                // Catat stock movement OUT untuk bahan
                StockMovement::create([
                    'item_type'      => 'raw_material',
                    'item_id'        => $material->id,
                    'movement_type'  => 'out',
                    'reference_type' => ProductionBatch::class,
                    'reference_id'   => $production->id,
                    'qty'            => $item['qty_used'],
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $material->current_stock,
                    'note'           => "Digunakan untuk produksi batch {$batchNumber}",
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.productions.show', $production)
                ->with('success', "Produksi {$batchNumber} berhasil disimpan.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan produksi: ' . $e->getMessage());
        }
    }

    /**
     * Detail satu produksi.
     */
    public function show(ProductionBatch $production)
    {
        $production->load('creator', 'items.rawMaterial.unit');
        return view('admin.productions.show', compact('production'));
    }
}

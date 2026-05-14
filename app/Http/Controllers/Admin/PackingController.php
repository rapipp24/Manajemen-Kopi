<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingItem;
use App\Models\PackingTransaction;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PackingController extends Controller
{
    /**
     * Ambil semua jenis produksi beserta stok curahnya masing-masing.
     * Stok curah per jenis = total output jenis itu − total berat yang sudah dipacking dari jenis itu.
     * Return: [ 'Kopi Robusta' => 60.0, 'Kopi Arabika' => 40.0, ... ]
     */
    private function getCurahStockAll(): array
    {
        // Total output per jenis produksi
        $produksi = ProductionBatch::select('product_type', DB::raw('SUM(total_output) as total'))
            ->groupBy('product_type')
            ->get()
            ->pluck('total', 'product_type')
            ->toArray();

        // Total berat yang sudah dipacking per jenis curah
        $packed = PackingTransaction::select('curah_type', DB::raw('SUM(packing_items.total_weight) as total'))
            ->join('packing_items', 'packing_items.packing_transaction_id', '=', 'packing_transactions.id')
            ->groupBy('curah_type')
            ->get()
            ->pluck('total', 'curah_type')
            ->toArray();

        // Hitung sisa per jenis
        $result = [];
        foreach ($produksi as $type => $totalOutput) {
            $sudahPacked = $packed[$type] ?? 0;
            $result[$type] = max(0, $totalOutput - $sudahPacked);
        }

        return $result; // [ 'Kopi Robusta' => 60.0, ... ]
    }

    /**
     * Stok curah untuk satu jenis tertentu.
     */
    private function getCurahStockByType(string $type): float
    {
        $all = $this->getCurahStockAll();
        return $all[$type] ?? 0.0;
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Daftar semua packing (dengan pagination).
     */
    public function index()
    {
        $packings = PackingTransaction::with('creator')
            ->orderBy('packing_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $curahStocks = $this->getCurahStockAll(); // array per jenis

        return view('admin.packings.index', compact('packings', 'curahStocks'));
    }

    /**
     * Form tambah packing baru.
     */
    public function create()
    {
        $products    = Product::where('is_active', true)->orderBy('name')->get();
        $curahStocks = $this->getCurahStockAll(); // array per jenis

        return view('admin.packings.create', compact('products', 'curahStocks'));
    }

    /**
     * Simpan packing baru ke database.
     */
    public function store(Request $request)
    {
        // ── 1. Validasi input ────────────────────────────────────────────────
        $request->validate([
            'packing_date'              => 'required|date',
            'curah_type'                => 'required|string',
            'note'                      => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.qty_pack'          => 'required|integer|min:1',
            'items.*.weight_per_pack'   => 'required|numeric|min:0.001',
        ]);

        $curahType = $request->curah_type;

        // ── 2. Hitung total berat yang akan dipakai (kg) ─────────────────────
        $totalBeratKg = 0;
        foreach ($request->items as $item) {
            $totalBeratKg += ($item['qty_pack'] * $item['weight_per_pack']) / 1000;
        }

        // ── 3. Validasi stok curah jenis yang dipilih ────────────────────────
        $curahTersedia = $this->getCurahStockByType($curahType);
        if ($curahTersedia < $totalBeratKg) {
            return back()->withInput()->with(
                'error',
                "Stok curah \"{$curahType}\" tidak mencukupi. " .
                "Dibutuhkan: " . number_format($totalBeratKg, 3) . " kg, " .
                "tersedia: " . number_format($curahTersedia, 3) . " kg."
            );
        }

        // ── 4. DB Transaction ────────────────────────────────────────────────
        DB::beginTransaction();

        try {
            // 4a. Generate nomor packing: PKG-YYYYMMDD-XXX
            $date  = date('Ymd');
            $count = PackingTransaction::whereDate('created_at', today())->count() + 1;
            $packingNumber = 'PKG-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // 4b. Buat header packing
            $packing = PackingTransaction::create([
                'packing_number' => $packingNumber,
                'packing_date'   => $request->packing_date,
                'curah_type'     => $curahType,
                'note'           => $request->note,
                'created_by'     => Auth::id(),
            ]);

            // 4c. Proses tiap item produk
            foreach ($request->items as $item) {
                $jumlahKemasan  = $item['qty_pack'];
                $beratPerKemasan = $item['weight_per_pack']; // gram
                $totalBeratItem  = ($jumlahKemasan * $beratPerKemasan) / 1000; // kg

                // Simpan item
                PackingItem::create([
                    'packing_transaction_id' => $packing->id,
                    'product_id'             => $item['product_id'],
                    'qty_pack'               => $jumlahKemasan,
                    'weight_per_pack'        => $beratPerKemasan,
                    'total_weight'           => $totalBeratItem,
                ]);

                // Tambah stok produk jadi
                $product     = Product::lockForUpdate()->find($item['product_id']);
                $stockBefore = $product->current_stock;
                $product->current_stock += $jumlahKemasan;
                $product->save();

                // Stock movement IN untuk produk jadi
                StockMovement::create([
                    'item_type'      => 'product',
                    'item_id'        => $product->id,
                    'movement_type'  => 'in',
                    'reference_type' => PackingTransaction::class,
                    'reference_id'   => $packing->id,
                    'qty'            => $jumlahKemasan,
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $product->current_stock,
                    'note'           => "Packing {$packingNumber} dari curah \"{$curahType}\": {$jumlahKemasan} pcs x {$beratPerKemasan}gr",
                ]);
            }

            // 4d. Stock movement OUT untuk curah (per jenis)
            $curahSetelah = $curahTersedia - $totalBeratKg;
            StockMovement::create([
                'item_type'      => 'raw_material',
                'item_id'        => 0, // 0 = stok curah agregat per jenis
                'movement_type'  => 'out',
                'reference_type' => PackingTransaction::class,
                'reference_id'   => $packing->id,
                'qty'            => $totalBeratKg,
                'stock_before'   => $curahTersedia,
                'stock_after'    => $curahSetelah,
                'note'           => "Packing {$packingNumber}: {$totalBeratKg} kg curah \"{$curahType}\" dipakai",
            ]);

            DB::commit();

            return redirect()
                ->route('admin.packings.show', $packing)
                ->with('success', "Packing {$packingNumber} berhasil disimpan.");

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan packing: ' . $e->getMessage());
        }
    }

    /**
     * Detail satu transaksi packing.
     */
    public function show(PackingTransaction $packing)
    {
        $packing->load('creator', 'items.product');
        return view('admin.packings.show', compact('packing'));
    }
}

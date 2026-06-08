<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageStock;
use App\Models\PackageAssembly;
use App\Models\PackageAssemblyItem;
use App\Models\PackageStockMovement;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class PackageAssemblyController extends Controller
{
    /**
     * Tampilkan riwayat perakitan paket
     */
    public function index()
    {
        $assemblies = PackageAssembly::with(['package', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.package-assemblies.index', compact('assemblies'));
    }

    /**
     * Form untuk merakit paket baru
     */
    public function create()
    {
        // Hanya paket aktif yang tidak didelete
        $packages = Package::with('items.product.unit')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.package-assemblies.create', compact('packages'));
    }

    /**
     * Simpan transaksi perakitan paket
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('packages', 'id')->whereNull('deleted_at')
            ],
            'qty'        => 'required|numeric|min:0.01',
            'note'       => 'nullable|string|max:1000',
        ], [
            'package_id.required' => 'Pilih paket terlebih dahulu.',
            'package_id.exists'   => 'Paket tidak ditemukan.',
            'qty.required'        => 'Kuantitas pembuatan wajib diisi.',
            'qty.numeric'         => 'Kuantitas pembuatan harus berupa angka.',
            'qty.min'             => 'Kuantitas pembuatan minimal adalah 0.01.',
        ]);

        DB::beginTransaction();

        try {
            // 1. Lock master paket
            $package = Package::lockForUpdate()->findOrFail($request->package_id);

            // Validasi apakah paket aktif
            if (!$package->is_active) {
                throw new Exception("Paket '{$package->name}' sedang tidak aktif dan tidak dapat dibuat.");
            }

            // Validasi apakah paket memiliki komponen
            $packageItems = $package->items()->with('product.unit')->get();
            if ($packageItems->isEmpty()) {
                throw new Exception("Paket '{$package->name}' tidak memiliki isi paket.");
            }

            // 2. Lock dan validasi stok semua komponen produk penyusun
            $productIds = $packageItems->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            // Cek kecukupan stok terlebih dahulu untuk semua item
            foreach ($packageItems as $item) {
                $product = $products->get($item->product_id);
                if (!$product) {
                    throw new Exception("Isi paket dengan ID {$item->product_id} tidak ditemukan.");
                }

                $totalQtyNeeded = $item->qty * $request->qty;
                if ($product->current_stock < $totalQtyNeeded) {
                    $available = number_format($product->current_stock, 2, ',', '.');
                    $needed = number_format($totalQtyNeeded, 2, ',', '.');
                    $unit = $product->unit->name ?? 'pcs';
                    throw new Exception("Stok isi paket '{$product->name}' tidak mencukupi. Dibutuhkan: {$needed} {$unit}, Tersedia: {$available} {$unit}.");
                }
            }

            // 3. Kalkulasi HPP Snapshot per 1 pack
            $hppPerPackageSnapshot = 0.00;
            foreach ($packageItems as $item) {
                $product = $products->get($item->product_id);
                $productCostPrice = $product->cost_price ?? 0.00;
                $hppPerPackageSnapshot += $item->qty * $productCostPrice;
            }

            // 4. Generate Nomor Assembly Unik
            $assemblyNumber = PackageAssembly::generateAssemblyNumber();

            // 5. Simpan Header Transaksi Perakitan
            $assembly = PackageAssembly::create([
                'assembly_number' => $assemblyNumber,
                'package_id'      => $package->id,
                'qty'             => $request->qty,
                'hpp_per_package_snapshot' => $hppPerPackageSnapshot,
                'note'            => $request->note,
                'created_by'      => Auth::id(),
            ]);

            // 6. Proses tiap komponen
            foreach ($packageItems as $item) {
                $product = $products->get($item->product_id);
                $totalQtyUsed = $item->qty * $request->qty;
                $productCostPrice = $product->cost_price ?? 0.00;

                // Simpan item detail perakitan
                PackageAssemblyItem::create([
                    'package_assembly_id' => $assembly->id,
                    'product_id'          => $item->product_id,
                    'qty_per_package'     => $item->qty,
                    'total_qty_used'      => $totalQtyUsed,
                    'cost_price_snapshot' => $productCostPrice,
                ]);

                // Kurangi stok produk penyusun di gudang
                $stockBefore = $product->current_stock;
                $product->current_stock -= $totalQtyUsed;
                $product->save();

                // Catat StockMovement keluar untuk produk penyusun
                StockMovement::create([
                    'item_type'      => 'product',
                    'item_id'        => $product->id,
                    'movement_type'  => 'out',
                    'reference_type' => PackageAssembly::class,
                    'reference_id'   => $assembly->id,
                    'qty'            => $totalQtyUsed,
                    'stock_before'   => $stockBefore,
                    'stock_after'    => $product->current_stock,
                    'note'           => "Digunakan untuk pembuatan paket {$assemblyNumber}",
                    'user_id'        => null, // Gudang Utama
                ]);
            }

            // 7. Update stok paket di gudang utama dengan safe locking
            $packageStock = PackageStock::where('package_id', $package->id)->lockForUpdate()->first();
            if (!$packageStock) {
                $packageStock = PackageStock::create([
                    'package_id' => $package->id,
                    'qty'        => 0.00,
                ]);
            }

            $packageStockBefore = $packageStock->qty;
            $packageStock->qty += $request->qty;
            $packageStock->save();

            // Catat PackageStockMovement masuk untuk paket
            PackageStockMovement::create([
                'package_id'     => $package->id,
                'user_id'        => null, // Gudang Utama
                'movement_type'  => 'in',
                'qty'            => $request->qty,
                'stock_before'   => $packageStockBefore,
                'stock_after'    => $packageStock->qty,
                'reference_type' => PackageAssembly::class,
                'reference_id'   => $assembly->id,
                'note'           => "Hasil pembuatan paket {$assemblyNumber}",
                'created_by'     => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.package-assemblies.show', $assembly->id)
                ->with('success', "Pembuatan stok paket {$assemblyNumber} berhasil disimpan.");

        } catch (QueryException $e) {
            DB::rollBack();
            // Cek unique constraint error untuk nomor assembly
            if ($e->errorInfo[1] == 1062) {
                return back()->withInput()->with('error', 'Terjadi bentrokan nomor transaksi pembuatan. Silakan coba simpan kembali.');
            }
            return back()->withInput()->with('error', 'Gagal memproses transaksi database: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Detail transaksi perakitan paket
     */
    public function show(PackageAssembly $packageAssembly)
    {
        $packageAssembly->load(['package', 'creator', 'items.product.unit']);

        return view('admin.package-assemblies.show', compact('packageAssembly'));
    }
}

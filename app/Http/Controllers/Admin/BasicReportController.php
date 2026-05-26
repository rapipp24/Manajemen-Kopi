<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\RawMaterialReceiptItem;
use App\Models\ProductionBatch;
use App\Models\Sale;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class BasicReportController extends Controller
{
    /**
     * Helper to parse and validate date inputs.
     */
    private function parseDates(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        return [$startDate, $endDate];
    }

    /**
     * Main index displaying Laporan Dasar.
     */
    public function index(Request $request)
    {
        list($startDate, $endDate) = $this->parseDates($request);

        // Validasi Backend: Tanggal awal tidak boleh lebih besar dari tanggal akhir
        if ($startDate->gt($endDate)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
        }

        $activeTab = $request->input('type', 'raw_material');

        // Fetch data based on active tab or fetch all to display on page tabs
        // To make page loading incredibly responsive, we only fetch what is needed or fetch all.
        // Let's fetch all because the user can switch tabs on the frontend or we can load them elegantly.
        // Eager Loading to avoid N+1 query issue.
        
        // 1. Bahan Baku
        $rawMaterials = RawMaterialReceiptItem::whereHas('receipt', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('receipt_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            })
            ->with(['receipt.supplier', 'rawMaterial.unit'])
            ->get();

        // 2. Produksi
        $productions = ProductionBatch::whereBetween('production_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with(['items.rawMaterial.unit'])
            ->orderBy('production_date', 'desc')
            ->get();

        // 3. Stok (Selalu stok saat ini)
        $rawMaterialsStock = RawMaterial::with('unit')->orderBy('name')->get();
        $productsStock = Product::with('unit')->where('is_active', true)->orderBy('name')->get();

        // 4. Penjualan
        $sales = Sale::whereBetween('sale_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with('customer')
            ->orderBy('sale_date', 'desc')
            ->get();

        // 5. Order
        $orders = SalesOrder::whereBetween('created_at', [$startDate, $endDate])
            ->with(['sales', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.basic-reports.index', compact(
            'startDate',
            'endDate',
            'activeTab',
            'rawMaterials',
            'productions',
            'rawMaterialsStock',
            'productsStock',
            'sales',
            'orders'
        ));
    }

    /**
     * Export active report to CSV (compatible with Excel via UTF-8 BOM & Semicolon)
     */
    public function exportExcel(Request $request)
    {
        list($startDate, $endDate) = $this->parseDates($request);

        if ($startDate->gt($endDate)) {
            return redirect()->back()->with('error', 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
        }

        $type = $request->input('type', 'raw_material');

        // 6. Nama file harus jelas: laporan-dasar-{jenis}-{tanggal_awal}-sampai-{tanggal_akhir}.csv
        // Dan untuk stok: laporan-dasar-stok-saat-ini.csv
        if ($type === 'stock') {
            $filename = "laporan-dasar-stok-saat-ini.csv";
        } else {
            $filename = "laporan-dasar-" . str_replace('_', '-', $type) . "-" . $startDate->format('d-m-Y') . "-sampai-" . $endDate->format('d-m-Y') . ".csv";
        }

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Tulis UTF-8 BOM agar Microsoft Excel langsung membaca sebagai UTF-8 dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($type) {
                case 'raw_material':
                    // Header Laporan
                    fputcsv($file, ['LAPORAN PEMBELIAN BAHAN BAKU', '', '', '', '', ''], ';');
                    fputcsv($file, ['Periode:', $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y'), '', '', '', ''], ';');
                    fputcsv($file, [], ';');

                    // Header Tabel (Pecah Qty & Satuan, Nominal angka mentah)
                    fputcsv($file, ['Tanggal', 'Supplier', 'Bahan Baku', 'Qty', 'Satuan', 'Total Harga'], ';');

                    // Data
                    $items = RawMaterialReceiptItem::whereHas('receipt', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('receipt_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                        })
                        ->with(['receipt.supplier', 'rawMaterial.unit'])
                        ->get();

                    if ($items->isEmpty()) {
                        fputcsv($file, ['Tidak ada data pada periode ini', '', '', '', '', ''], ';');
                    } else {
                        foreach ($items as $item) {
                            fputcsv($file, [
                                Carbon::parse($item->receipt->receipt_date)->format('d-m-Y'),
                                $item->receipt->supplier->name ?? '—',
                                $item->rawMaterial->name ?? '—',
                                $item->qty, // Qty angka mentah
                                $item->rawMaterial->unit->code ?? '', // Satuan terpisah
                                $item->subtotal // Nominal angka mentah
                            ], ';');
                        }
                    }
                    break;

                case 'production':
                    fputcsv($file, ['LAPORAN PRODUKSI KOPI', '', '', '', ''], ';');
                    fputcsv($file, ['Periode:', $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y'), '', '', ''], ';');
                    fputcsv($file, [], ';');

                    fputcsv($file, ['Tanggal', 'Nomor Batch', 'Bahan Digunakan', 'Hasil Produksi (gr)', 'Susut (gr)'], ';');

                    $batches = ProductionBatch::whereBetween('production_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->with(['items.rawMaterial.unit'])
                        ->orderBy('production_date', 'desc')
                        ->get();

                    if ($batches->isEmpty()) {
                        fputcsv($file, ['Tidak ada data pada periode ini', '', '', '', ''], ';');
                    } else {
                        foreach ($batches as $batch) {
                            $materialsStr = $batch->items->map(function ($item) {
                                return ($item->rawMaterial->name ?? '—') . ' (' . number_format($item->qty_used, 0, ',', '.') . ' ' . ($item->rawMaterial->unit->code ?? '') . ')';
                            })->implode(', ');

                            fputcsv($file, [
                                Carbon::parse($batch->production_date)->format('d-m-Y'),
                                $batch->batch_number,
                                $materialsStr,
                                $batch->total_output, // Qty mentah (gr)
                                $batch->shrinkage // Susut mentah (gr)
                            ], ';');
                        }
                    }
                    break;

                case 'stock':
                    fputcsv($file, ['LAPORAN STOK AKTUAL (REAL-TIME)', '', '', '', ''], ';');
                    fputcsv($file, ['Kondisi per tanggal:', now()->format('d-m-Y H:i'), '', '', ''], ';');
                    fputcsv($file, ['* Catatan: Laporan stok menunjukkan kondisi saat ini, bukan histori berdasarkan filter tanggal.', '', '', '', ''], ';');
                    fputcsv($file, [], ';');

                    fputcsv($file, ['Tipe Item', 'Nama Item', 'Stok Saat Ini', 'Satuan', 'Status'], ';');

                    $raws = RawMaterial::with('unit')->orderBy('name')->get();
                    $prods = Product::with('unit')->where('is_active', true)->orderBy('name')->get();

                    if ($raws->isEmpty() && $prods->isEmpty()) {
                        fputcsv($file, ['Tidak ada data pada periode ini', '', '', '', ''], ';');
                    } else {
                        foreach ($raws as $r) {
                            $status = $r->current_stock <= $r->minimum_stock ? 'Kritis / Hampir Habis' : 'Aman';
                            fputcsv($file, [
                                'Bahan Baku',
                                $r->name,
                                $r->current_stock, // Angka mentah
                                $r->unit->code ?? '',
                                $status
                            ], ';');
                        }

                        foreach ($prods as $p) {
                            $status = $p->current_stock <= 0 ? 'Habis' : 'Tersedia';
                            fputcsv($file, [
                                'Barang Jadi',
                                $p->name,
                                $p->current_stock, // Angka mentah
                                $p->unit->code ?? 'pcs',
                                $status
                            ], ';');
                        }
                    }
                    break;

                case 'sale':
                    fputcsv($file, ['LAPORAN PENJUALAN RETAIL/DIRECT ADMIN', '', '', ''], ';');
                    fputcsv($file, ['Periode:', $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y'), '', '', ''], ';');
                    fputcsv($file, ['* Catatan: Penjualan dari Sales Lapangan disetorkan terpisah (tidak masuk direct invoice).', '', '', ''], ';');
                    fputcsv($file, [], ';');

                    fputcsv($file, ['Tanggal Penjualan', 'Nomor Invoice', 'Customer', 'Total Penjualan'], ';');

                    $sales = Sale::whereBetween('sale_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->with('customer')
                        ->orderBy('sale_date', 'desc')
                        ->get();

                    if ($sales->isEmpty()) {
                        fputcsv($file, ['Tidak ada data pada periode ini', '', '', ''], ';');
                    } else {
                        foreach ($sales as $sale) {
                            fputcsv($file, [
                                Carbon::parse($sale->sale_date)->format('d-m-Y'),
                                $sale->invoice_number,
                                $sale->customer_name ?? ($sale->customer->name ?? 'Umum/Retail'),
                                $sale->total_amount // Nominal mentah
                            ], ';');
                        }
                    }
                    break;

                case 'order':
                    fputcsv($file, ['LAPORAN PENGAJUAN BARANG SALES', '', '', '', ''], ';');
                    fputcsv($file, ['Periode:', $startDate->format('d-m-Y') . ' s/d ' . $endDate->format('d-m-Y'), '', '', ''], ';');
                    fputcsv($file, [], ';');

                    fputcsv($file, ['Tanggal Pengajuan', 'Sales Pengaju', 'Produk Detail', 'Status Pengajuan', 'Total Nilai'], ';');

                    $orders = SalesOrder::whereBetween('created_at', [$startDate, $endDate])
                        ->with(['sales', 'items.product'])
                        ->orderBy('created_at', 'desc')
                        ->get();

                    if ($orders->isEmpty()) {
                        fputcsv($file, ['Tidak ada data pada periode ini', '', '', '', ''], ';');
                    } else {
                        foreach ($orders as $order) {
                            $productsStr = $order->items->map(function ($item) {
                                return ($item->product->name ?? '—') . ' (' . number_format($item->qty, 0, ',', '.') . ' pcs)';
                            })->implode(', ');

                            fputcsv($file, [
                                $order->created_at->format('d-m-Y H:i'),
                                $order->sales->name ?? '—',
                                $productsStr,
                                ucfirst($order->status),
                                $order->total // Nominal mentah
                            ], ';');
                        }
                    }
                    break;
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Render high-quality printable layout for PDF window.print()
     */
    public function exportPdf(Request $request)
    {
        list($startDate, $endDate) = $this->parseDates($request);

        if ($startDate->gt($endDate)) {
            return redirect()->route('admin.basic-reports.index')->with('error', 'Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
        }

        $type = $request->input('type', 'raw_material');

        // Fetch specific data for target printable page
        $rawMaterials = collect();
        $productions = collect();
        $rawMaterialsStock = collect();
        $productsStock = collect();
        $sales = collect();
        $orders = collect();

        switch ($type) {
            case 'raw_material':
                $rawMaterials = RawMaterialReceiptItem::whereHas('receipt', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('receipt_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                    })
                    ->with(['receipt.supplier', 'rawMaterial.unit'])
                    ->get();
                $title = "Laporan Penerimaan Bahan Baku";
                break;

            case 'production':
                $productions = ProductionBatch::whereBetween('production_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->with(['items.rawMaterial.unit'])
                    ->orderBy('production_date', 'desc')
                    ->get();
                $title = "Laporan Produksi Kopi";
                break;

            case 'stock':
                $rawMaterialsStock = RawMaterial::with('unit')->orderBy('name')->get();
                $productsStock = Product::with('unit')->where('is_active', true)->orderBy('name')->get();
                $title = "Laporan Stok Aktual (Real-Time)";
                break;

            case 'sale':
                $sales = Sale::whereBetween('sale_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->with('customer')
                    ->orderBy('sale_date', 'desc')
                    ->get();
                $title = "Laporan Penjualan Direct Admin";
                break;

            case 'order':
                $orders = SalesOrder::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['sales', 'items.product'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $title = "Laporan Pengajuan Barang Sales";
                break;
            
            default:
                abort(404, "Laporan tidak ditemukan.");
        }

        return view('admin.basic-reports.pdf', compact(
            'startDate',
            'endDate',
            'type',
            'title',
            'rawMaterials',
            'productions',
            'rawMaterialsStock',
            'productsStock',
            'sales',
            'orders'
        ));
    }
}

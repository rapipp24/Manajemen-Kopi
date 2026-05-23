<?php
/**
 * DRY-RUN AUDIT: Delivery Report Total Amount = 0
 * 
 * Script ini HANYA MEMBACA data, TIDAK mengubah database.
 * Tujuan: Menemukan delivery_reports yang total_amount = 0
 *         padahal delivery_report_items.subtotal > 0.
 */

echo "============================================================\n";
echo "  DRY-RUN AUDIT — Delivery Report Total Amount Mismatch\n";
echo "  Tanggal: " . now()->format('d M Y, H:i:s') . "\n";
echo "  Mode: READ-ONLY (tidak ada perubahan database)\n";
echo "============================================================\n\n";

// 1. Cari semua DR yang total_amount = 0 dan punya items
$reports = \App\Models\DeliveryReport::where('total_amount', 0)
    ->whereHas('items')
    ->with(['items', 'sales', 'customer'])
    ->get();

// 2. Hitung total return diterima per DR (batch query, hindari N+1)
$returnPerReport = [];
if ($reports->isNotEmpty()) {
    $returnPerReport = \App\Models\SalesReturnItem::join(
            'sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id'
        )
        ->where('sales_returns.status', 'diterima')
        ->whereIn('sales_returns.delivery_report_id', $reports->pluck('id'))
        ->groupBy('sales_returns.delivery_report_id')
        ->selectRaw('sales_returns.delivery_report_id, SUM(sales_return_items.subtotal_return) as total_return')
        ->pluck('total_return', 'delivery_report_id')
        ->all();
}

echo "A. Jumlah Delivery Report bermasalah: " . $reports->count() . "\n\n";

if ($reports->isEmpty()) {
    echo "   Tidak ada Delivery Report bermasalah ditemukan.\n";
    echo "   Dry-run selesai.\n";
    return;
}

// 3. Bangun tabel detail
echo "B. Detail per Delivery Report:\n";
echo str_repeat("─", 120) . "\n";

$no = 0;
$totalOldAmount = 0;
$totalNewAmount = 0;
$totalSisaTagihan = 0;
$reportsToUpdate = [];

foreach ($reports as $r) {
    $no++;

    $tokoName = $r->customer->name ?? $r->customer_name_manual ?? '—';
    $salesName = $r->sales->name ?? '—';
    $totalItemSubtotal = (float) $r->items->sum('subtotal');
    $totalReturn = (float) ($returnPerReport[$r->id] ?? 0);
    $dpAmount = (float) $r->down_payment_amount;
    $tagihanSetelahReturn = $totalItemSubtotal - $totalReturn;
    $sisaTagihan = $tagihanSetelahReturn - $dpAmount;
    $munculDropdown = $sisaTagihan > 0 ? 'YA' : 'TIDAK';

    // Cek anomali
    $anomali = false;
    $anomaliNote = '';
    if ($totalItemSubtotal <= 0) {
        $anomali = true;
        $anomaliNote = 'Subtotal item = 0';
    }
    if ($tagihanSetelahReturn < 0) {
        $anomali = true;
        $anomaliNote = 'Tagihan setelah return negatif';
    }

    $rekomendasi = $anomali ? "SKIP — {$anomaliNote}" : 'UPDATE total_amount';

    if (!$anomali) {
        $reportsToUpdate[] = $r->report_number;
        $totalSisaTagihan += max(0, $sisaTagihan);
    }

    $totalOldAmount += (float) $r->total_amount;
    $totalNewAmount += $totalItemSubtotal;

    echo "\n  [{$no}] {$r->report_number}\n";
    echo "  ├─ ID                        : {$r->id}\n";
    echo "  ├─ Toko                      : {$tokoName}\n";
    echo "  ├─ Sales                     : {$salesName} (ID: {$r->sales_id})\n";
    echo "  ├─ Tanggal Kirim             : " . ($r->delivery_date ? $r->delivery_date->format('d-m-Y') : '—') . "\n";
    echo "  ├─ Payment Status            : {$r->payment_status}\n";
    echo "  ├─ total_amount (saat ini)   : Rp " . number_format($r->total_amount, 0, ',', '.') . "\n";
    echo "  ├─ SUM(items.subtotal)       : Rp " . number_format($totalItemSubtotal, 0, ',', '.') . "\n";
    echo "  ├─ Total Return Diterima     : Rp " . number_format($totalReturn, 0, ',', '.') . "\n";
    echo "  ├─ Down Payment / Dibayar    : Rp " . number_format($dpAmount, 0, ',', '.') . "\n";
    echo "  ├─ Tagihan Setelah Return    : Rp " . number_format($tagihanSetelahReturn, 0, ',', '.') . "\n";
    echo "  ├─ Sisa Tagihan Setelah Fix  : Rp " . number_format($sisaTagihan, 0, ',', '.') . "\n";
    echo "  ├─ Muncul di Dropdown Setoran: {$munculDropdown}\n";
    echo "  └─ Rekomendasi               : {$rekomendasi}\n";
}

echo "\n" . str_repeat("─", 120) . "\n";

// 4. Ringkasan
echo "\nC. Ringkasan:\n";
echo "  ├─ total_amount lama keseluruhan          : Rp " . number_format($totalOldAmount, 0, ',', '.') . "\n";
echo "  ├─ total_amount baru keseluruhan           : Rp " . number_format($totalNewAmount, 0, ',', '.') . "\n";
echo "  ├─ Sisa tagihan yang akan aktif setelah fix: Rp " . number_format($totalSisaTagihan, 0, ',', '.') . "\n";
echo "  ├─ Jumlah DR yang akan diupdate            : " . count($reportsToUpdate) . "\n";
echo "  └─ Daftar report yang akan diupdate        :\n";
foreach ($reportsToUpdate as $rn) {
    echo "       • {$rn}\n";
}

// 5. Konfirmasi keamanan
echo "\nD. Konfirmasi Keamanan:\n";
echo "  ✓ Tidak ada data yang diubah\n";
echo "  ✓ Tidak ada stok yang diubah\n";
echo "  ✓ Tidak ada payment yang diubah\n";
echo "  ✓ Tidak ada return yang diubah\n";
echo "  ✓ Script ini hanya membaca (SELECT)\n";

// 6. Rekomendasi
echo "\nE. Rekomendasi:\n";
$skipCount = $no - count($reportsToUpdate);
if ($skipCount > 0) {
    echo "  ⚠ Ada {$skipCount} laporan yang perlu dicek manual sebelum live update.\n";
} else {
    echo "  ✓ Semua laporan aman untuk di-update.\n";
}
echo "  → Jika disetujui, lanjut Tahap 2: Live fix dengan DB transaction.\n";
echo "  → total_amount akan diisi dari SUM(delivery_report_items.subtotal).\n";
echo "  → Field lain tidak akan diubah.\n";

echo "\n============================================================\n";
echo "  DRY-RUN SELESAI — Tidak ada perubahan database\n";
echo "============================================================\n";

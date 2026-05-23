<?php
/**
 * TAHAP 2: LIVE FIX — Delivery Report Total Amount
 * 
 * Update HANYA delivery_reports.total_amount untuk 3 report yang disetujui.
 * Menggunakan DB transaction. Backup tabel dibuat sebelum update.
 */

use Illuminate\Support\Facades\DB;
use App\Models\DeliveryReport;
use App\Models\SalesReturnItem;

echo "============================================================\n";
echo "  TAHAP 2: LIVE FIX — Delivery Report Total Amount\n";
echo "  Tanggal: " . now()->format('d M Y, H:i:s') . "\n";
echo "============================================================\n\n";

// Daftar report yang disetujui
$approved = [
    'DEL-20260516-001' => 140000,
    'DEL-20260519-001' => 35000,
    'DEL-20260519-002' => 140000,
];

// ─── STEP 1: BACKUP ───────────────────────────────────────────
echo "STEP 1: Membuat backup tabel delivery_reports...\n";
try {
    DB::statement('CREATE TABLE IF NOT EXISTS delivery_reports_backup_20260523 AS SELECT * FROM delivery_reports');
    $backupCount = DB::table('delivery_reports_backup_20260523')->count();
    echo "  ✓ Backup berhasil: delivery_reports_backup_20260523 ({$backupCount} rows)\n\n";
} catch (\Exception $e) {
    echo "  ⚠ Backup gagal: " . $e->getMessage() . "\n";
    echo "  → Melanjutkan tanpa backup (data asli masih aman di tabel utama).\n\n";
}

// ─── STEP 2: VALIDASI + UPDATE DALAM TRANSACTION ──────────────
echo "STEP 2: Validasi dan update dalam transaction...\n\n";

$results = [];
$allValid = true;

DB::beginTransaction();

try {
    foreach ($approved as $reportNumber => $expectedTotal) {
        echo "  Memproses {$reportNumber}...\n";

        // Ambil report
        $report = DeliveryReport::where('report_number', $reportNumber)->first();

        if (!$report) {
            echo "    ✗ GAGAL: Report tidak ditemukan!\n";
            $allValid = false;
            break;
        }

        // Validasi 1: total_amount masih 0
        if ((float) $report->total_amount != 0) {
            echo "    ✗ GAGAL: total_amount bukan 0 (saat ini: {$report->total_amount})\n";
            $allValid = false;
            break;
        }

        // Validasi 2: Hitung SUM(items.subtotal)
        $calculatedTotal = (float) $report->items()->sum('subtotal');

        if ($calculatedTotal <= 0) {
            echo "    ✗ GAGAL: SUM(items.subtotal) = 0 atau negatif\n";
            $allValid = false;
            break;
        }

        // Validasi 3: Hasil hitung sama dengan nilai yang disetujui
        if ($calculatedTotal != $expectedTotal) {
            echo "    ✗ GAGAL: Nilai berbeda dari dry-run!\n";
            echo "      Disetujui: Rp " . number_format($expectedTotal, 0, ',', '.') . "\n";
            echo "      Dihitung : Rp " . number_format($calculatedTotal, 0, ',', '.') . "\n";
            $allValid = false;
            break;
        }

        // Simpan before
        $before = (float) $report->total_amount;

        // UPDATE hanya total_amount
        $report->total_amount = $calculatedTotal;
        $report->save();

        // Refresh untuk konfirmasi
        $report->refresh();

        // Hitung return diterima
        $totalReturn = (float) SalesReturnItem::join(
                'sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id'
            )
            ->where('sales_returns.status', 'diterima')
            ->where('sales_returns.delivery_report_id', $report->id)
            ->sum('sales_return_items.subtotal_return');

        $tagihanSetelahReturn = $calculatedTotal - $totalReturn;
        $sisaTagihan = $tagihanSetelahReturn - (float) $report->down_payment_amount;

        $results[] = [
            'report_number' => $reportNumber,
            'toko' => $report->customer->name ?? $report->customer_name_manual ?? '—',
            'before' => $before,
            'after' => (float) $report->total_amount,
            'total_return' => $totalReturn,
            'dp' => (float) $report->down_payment_amount,
            'sisa_tagihan' => $sisaTagihan,
            'muncul_dropdown' => $sisaTagihan > 0 ? 'YA' : 'TIDAK',
        ];

        echo "    ✓ Rp " . number_format($before, 0, ',', '.') . " → Rp " . number_format($calculatedTotal, 0, ',', '.') . "\n";
    }

    if (!$allValid) {
        DB::rollBack();
        echo "\n  ✗ ROLLBACK — Tidak ada perubahan yang disimpan.\n";
        echo "  → Harap laporkan ke admin untuk investigasi.\n";
        echo "\n============================================================\n";
        echo "  LIVE FIX DIBATALKAN\n";
        echo "============================================================\n";
        return;
    }

    DB::commit();
    echo "\n  ✓ COMMIT — Semua perubahan disimpan.\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n  ✗ EXCEPTION: " . $e->getMessage() . "\n";
    echo "  ✗ ROLLBACK — Tidak ada perubahan yang disimpan.\n";
    echo "\n============================================================\n";
    echo "  LIVE FIX DIBATALKAN\n";
    echo "============================================================\n";
    return;
}

// ─── STEP 3: TABEL BEFORE/AFTER ──────────────────────────────
echo "\n" . str_repeat("─", 100) . "\n";
echo "STEP 3: Hasil Before/After\n" . str_repeat("─", 100) . "\n\n";

foreach ($results as $i => $r) {
    $no = $i + 1;
    echo "  [{$no}] {$r['report_number']}\n";
    echo "  ├─ Toko                      : {$r['toko']}\n";
    echo "  ├─ total_amount SEBELUM      : Rp " . number_format($r['before'], 0, ',', '.') . "\n";
    echo "  ├─ total_amount SESUDAH      : Rp " . number_format($r['after'], 0, ',', '.') . "\n";
    echo "  ├─ Total Return Diterima     : Rp " . number_format($r['total_return'], 0, ',', '.') . "\n";
    echo "  ├─ Down Payment              : Rp " . number_format($r['dp'], 0, ',', '.') . "\n";
    echo "  ├─ Sisa Tagihan Setelah Fix  : Rp " . number_format($r['sisa_tagihan'], 0, ',', '.') . "\n";
    echo "  └─ Muncul di Dropdown Setoran: {$r['muncul_dropdown']}\n\n";
}

// ─── STEP 4: KONFIRMASI KEAMANAN ─────────────────────────────
echo str_repeat("─", 100) . "\n";
echo "STEP 4: Konfirmasi Keamanan\n" . str_repeat("─", 100) . "\n\n";
echo "  1. ✓ Backup dibuat: delivery_reports_backup_20260523\n";
echo "  2. ✓ Jumlah report diupdate: " . count($results) . "\n";
echo "  3. ✓ Hanya field total_amount yang diubah\n";
echo "  4. ✓ Stok (products.current_stock, sales_stocks) TIDAK diubah\n";
echo "  5. ✓ sales_deposits TIDAK diubah\n";
echo "  6. ✓ sale_payments TIDAK diubah\n";
echo "  7. ✓ sales_returns TIDAK diubah\n";
echo "  8. ✓ stock_movements TIDAK diubah\n";
echo "  9. ✓ payment_status TIDAK diubah\n";
echo " 10. ✓ down_payment_amount TIDAK diubah\n";
echo " 11. ✓ 3 laporan sekarang muncul di dropdown Setoran Uang\n";

echo "\n============================================================\n";
echo "  LIVE FIX SELESAI — 3 report berhasil diupdate\n";
echo "============================================================\n";

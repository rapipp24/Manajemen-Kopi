<x-layouts.user>
    <x-slot name="title">Detail Setoran</x-slot>

    <style>
        .page-header { margin-bottom:24px; }
        .page-title  { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.03em; }

        .btn-secondary {
            background:#fff;border:1px solid #ece8e3;color:#44403c;padding:9px 16px;border-radius:9px;
            text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s;
        }
        .btn-secondary:hover { background:#fafaf8; }

        .detail-card { background:#fff;border:1px solid #ece8e3;border-radius:12px;padding:24px;max-width:600px; }

        .info-row { display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f5f0eb;font-size:13.5px; }
        .info-row:last-child { border-bottom:none; }
        .info-label { color:#78716c;font-weight:500; }
        .info-value { color:#1c1917;font-weight:600;text-align:right; }

        .status-badge {
            font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;display:inline-block;
        }

        .alert-rejection {
            background:#fef2f2;border:1px solid #fecaca;color:#991b1b;
            padding:14px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;
        }
    </style>

    <div style="margin-bottom:16px;">
        <a href="{{ route('sales.deposits.index') }}" class="btn-secondary">
            ← Kembali ke Daftar
        </a>
    </div>

    <div class="page-header">
        <h1 class="page-title">Detail Pengajuan Setoran</h1>
    </div>

    @if($deposit->status === 'ditolak')
        <div class="alert-rejection">
            <h4 style="font-weight:700;margin-bottom:4px;">Setoran Ditolak</h4>
            <p><strong>Alasan:</strong> {{ $deposit->rejection_reason ?? 'Tidak dicantumkan alasan.' }}</p>
        </div>
    @endif

    <div class="detail-card">
        <div class="info-row">
            <span class="info-label">No. Setoran</span>
            <span class="info-value" style="font-family:monospace;font-size:14px;color:#92400e;">{{ $deposit->deposit_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Laporan Pengiriman</span>
            <span class="info-value" style="font-family:monospace;font-size:14px;">
                <a href="{{ route('sales.delivery-reports.show', $deposit->delivery_report_id) }}" style="color:#92400e;text-decoration:none;">
                    {{ $deposit->deliveryReport->report_number }}
                </a>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Toko Tujuan</span>
            <span class="info-value">{{ $deposit->deliveryReport->toko_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nominal Uang</span>
            <span class="info-value" style="font-size:16px;color:#15803d;font-weight:800;">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Penagihan</span>
            <span class="info-value">{{ $deposit->payment_date->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Metode Pembayaran</span>
            <span class="info-value">{{ $deposit->payment_method }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Catatan Sales</span>
            <span class="info-value" style="font-weight:normal;color:#44403c;max-width:300px;word-break:break-word;">{{ $deposit->note ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status Verifikasi</span>
            <span class="info-value">
                @if($deposit->status === 'disetujui')
                    <span class="status-badge" style="background:#dcfce7;color:#166534;">DISETUJUI</span>
                @elseif($deposit->status === 'ditolak')
                    <span class="status-badge" style="background:#fee2e2;color:#991b1b;">DITOLAK</span>
                @else
                    <span class="status-badge" style="background:#fef08a;color:#854d0e;">MENUNGGU VERIFIKASI</span>
                @endif
            </span>
        </div>

        @if($deposit->status !== 'menunggu_verifikasi' && $deposit->verifier)
            <div class="info-row" style="background:#fafaf8;margin:12px -24px -24px;padding:16px 24px;border-top:1px solid #ece8e3;border-bottom-left-radius:12px;border-bottom-right-radius:12px;">
                <span class="info-label">Diverifikasi oleh</span>
                <span class="info-value" style="font-weight:normal;font-size:12.5px;color:#78716c;">
                    <strong style="color:#1c1917;">{{ $deposit->verifier->name }}</strong><br>
                    Pada {{ $deposit->verified_at->format('d M Y, H:i') }}
                </span>
            </div>
        @endif
    </div>
</x-layouts.user>

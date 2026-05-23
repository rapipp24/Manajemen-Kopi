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
        <div class="info-row" style="flex-direction: column; align-items: flex-start; gap: 8px;">
            <span class="info-label">Bukti Pembayaran</span>
            <span class="info-value" style="text-align: left; width: 100%;">
                @if($deposit->payment_proof_path)
                    @php
                        $fileExtension = strtolower(pathinfo($deposit->payment_proof_path, PATHINFO_EXTENSION));
                    @endphp
                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp']))
                        <div style="margin-top: 4px;">
                            <img src="{{ Storage::url($deposit->payment_proof_path) }}" alt="Bukti Pembayaran" style="max-width: 180px; max-height: 180px; border-radius: 8px; border: 1px solid #ece8e3; display: block; margin-bottom: 8px;">
                            <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" class="btn-secondary" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                Lihat Bukti Pembayaran
                            </a>
                        </div>
                    @else
                        <div style="margin-top: 4px;">
                            <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" class="btn-secondary" style="padding: 8px 14px; font-size: 12.5px; text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                Lihat Bukti Pembayaran (PDF)
                            </a>
                        </div>
                    @endif
                @else
                    <span style="color: #78716c; font-style: italic; font-weight: normal;">Tidak ada bukti pembayaran</span>
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

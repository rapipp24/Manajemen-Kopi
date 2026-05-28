<x-layouts.user>
    <x-slot name="title">Detail Setoran {{ $deposit->deposit_number }}</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13.5px;font-weight:600;color:var(--muted);text-decoration:none;margin-bottom:20px;transition:color 0.15s; }
        .back-link:hover { color:var(--text); }

        /* ── Page heading ──────────────────── */
        .deposit-heading { display:flex;align-items:center;gap:12px;margin-bottom:22px;flex-wrap:wrap; }
        .deposit-number  { font-size:17px;font-weight:700;color:var(--text);font-family:monospace;letter-spacing:0.02em; }
        
        .badge { display:inline-block;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;line-height:1.3;text-transform:uppercase;letter-spacing:0.04em; }
        .badge-pending  { background:#fffbeb;color:#b45309;border:1px solid #fef3c7; }
        .badge-approved { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
        .badge-canceled { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }

        /* ── Layout ──────────────────────── */
        .layout { display:grid;grid-template-columns:1fr 280px;gap:16px;align-items:start; }

        /* ── Card ────────────────────────── */
        .card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .card-header { padding:13px 18px;border-bottom:1px solid var(--border);background:var(--cream); }
        .card-header h3 { font-size:13.5px;font-weight:700;color:var(--text);margin:0; }

        /* ── Info Rows ───────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:12px 18px;border-bottom:1px solid var(--border);font-size:13.5px; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em; }
        .info-value { font-size:13.5px;font-weight:600;color:var(--text);text-align:right;max-width:58%; }

        .btn-secondary {
            background:#fff;border:1px solid var(--border);color:var(--text);padding:8px 14px;border-radius:8px;
            text-decoration:none;font-size:12.5px;font-weight:600;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s;
        }
        .btn-secondary:hover { background:var(--cream); }

        .alert-rejection {
            background:#fef2f2;border:1px solid #fecaca;color:#991b1b;
            padding:14px 16px;border-radius:10px;font-size:13.5px;margin-bottom:20px;
        }

        /* ── Responsive ──────────────────── */
        @media (max-width:768px) { .layout { grid-template-columns:1fr; } }
    </style>

    <a href="{{ route('sales.deposits.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar
    </a>

    @php
        $statusMap = [
            'menunggu_verifikasi' => ['badge-pending', 'Menunggu Verifikasi'],
            'disetujui'           => ['badge-approved', 'Disetujui'],
            'ditolak'             => ['badge-canceled', 'Ditolak'],
        ];
        [$badgeCls, $badgeLbl] = $statusMap[$deposit->status] ?? ['badge-pending', $deposit->status];
    @endphp

    <div class="deposit-heading">
        <span class="deposit-number">{{ $deposit->deposit_number }}</span>
        <span class="badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
    </div>

    @if($deposit->status === 'ditolak')
        <div class="alert-rejection">
            <h4 style="font-weight:700;margin-bottom:4px;display:flex;align-items:center;gap:6px;">
                <i data-lucide="alert-circle" style="width:16px;height:16px;"></i> Setoran Ditolak
            </h4>
            <p style="margin-top:4px;"><strong>Alasan:</strong> {{ $deposit->rejection_reason ?? 'Tidak dicantumkan alasan.' }}</p>
        </div>
    @endif

    <div class="layout">
        {{-- Kiri: Rincian Setoran --}}
        <div class="card">
            <div class="card-header">
                <h3>Rincian Pengajuan Setoran</h3>
            </div>
            
            <div class="info-row">
                <span class="info-label">No. Setoran</span>
                <span class="info-value" style="font-family:monospace;font-size:14px;color:var(--brown);">{{ $deposit->deposit_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Laporan Pengiriman</span>
                <span class="info-value" style="font-family:monospace;font-size:14px;">
                    <a href="{{ route('sales.delivery-reports.show', $deposit->delivery_report_id) }}" style="color:var(--accent);text-decoration:none;font-weight:700;">
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
                <span class="info-value" style="font-size:16px;color:#166534;font-weight:800;">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</span>
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
                <span class="info-value" style="font-weight:normal;color:var(--muted);max-width:300px;word-break:break-word;">{{ $deposit->note ?? '—' }}</span>
            </div>
        </div>

        {{-- Kanan: Bukti & Verifikator --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card">
                <div class="card-header">
                    <h3>Bukti Pembayaran</h3>
                </div>
                <div style="padding:16px; text-align:center;">
                    @if($deposit->payment_proof_path)
                        @php
                            $fileExtension = strtolower(pathinfo($deposit->payment_proof_path, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp']))
                            <div style="margin-top: 4px;">
                                <img src="{{ Storage::url($deposit->payment_proof_path) }}" alt="Bukti Pembayaran" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); display: block; margin-bottom: 12px;">
                                <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" class="btn-secondary" style="width:100%; justify-content:center;">
                                    <i data-lucide="external-link" style="width:14px;height:14px;"></i> Lihat Gambar Penuh
                                </a>
                            </div>
                        @else
                            <div style="padding:12px 0;">
                                <i data-lucide="file-text" style="width:32px;height:32px;color:var(--accent);margin:0 auto 10px;display:block;"></i>
                                <a href="{{ Storage::url($deposit->payment_proof_path) }}" target="_blank" class="btn-secondary" style="width:100%; justify-content:center;">
                                    <i data-lucide="download" style="width:14px;height:14px;"></i> Unduh Dokumen PDF
                                </a>
                            </div>
                        @endif
                    @else
                        <div style="padding:16px 0; color: var(--muted); font-style: italic; font-size:12.5px;">
                            <i data-lucide="info" style="width:20px;height:20px;color:var(--accent);margin:0 auto 8px;display:block;"></i>
                            Tidak ada bukti pembayaran dilampirkan (Tunai)
                        </div>
                    @endif
                </div>
            </div>

            @if($deposit->status !== 'menunggu_verifikasi' && $deposit->verifier)
                <div class="card">
                    <div class="card-header">
                        <h3>Verifikator</h3>
                    </div>
                    <div style="padding:16px; font-size:13px; color:var(--text);">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#16a34a;"></div>
                            <span style="font-weight:700;">Diverifikasi oleh:</span>
                        </div>
                        <div style="font-weight:600; padding-left:16px; color:var(--brown);">{{ $deposit->verifier->name }}</div>
                        <div style="font-size:11.5px; color:var(--muted); padding-left:16px; margin-top:2px;">
                            {{ $deposit->verified_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.user>

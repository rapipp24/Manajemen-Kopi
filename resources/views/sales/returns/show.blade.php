<x-layouts.user>
    <x-slot name="title">Detail Return {{ $return->return_number }}</x-slot>

    <style>

        /* ── Page heading ──────────────────── */
        .return-heading { display:flex;align-items:center;gap:12px;margin-bottom:22px;flex-wrap:wrap; }
        .return-number  { font-size:17px;font-weight:700;color:var(--text);font-family:monospace;letter-spacing:0.02em; }
        
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

        /* ── Table ───────────────────────── */
        .table-scroll-container { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:var(--cream); border-bottom:1px solid var(--border); }
        th { padding:12px 18px; text-align:left; font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:0.07em; }
        td { padding:14px 18px; border-bottom:1px solid var(--border); font-size:13.5px; color:var(--text); vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--cream); }

        /* ── Info Rows ───────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:12px 18px;border-bottom:1px solid var(--border);font-size:13.5px; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em; }
        .info-value { font-size:13.5px;font-weight:600;color:var(--text);text-align:right;max-width:58%; }

        .btn-secondary {
            background:#fff;border:1px solid var(--border);color:var(--text);padding:8px 14px;border-radius:8px;
            text-decoration:none;font-size:12.5px;font-weight:600;display:inline-flex;align-items:center;gap:6px;
            transition:background 0.15s; width: 100%; justify-content: center;
        }
        .btn-secondary:hover { background:var(--cream); }

        /* ── Desktop/Mobile Dual Layout ──────── */
        .desktop-only { display: block; }
        .mobile-only { display: none; }

        .mobile-item {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .mobile-item:last-child { border-bottom: none; }
        .mobile-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }
        .mobile-item-title { font-weight: 700; color: var(--text); font-size: 13.5px; }
        .mobile-item-qty { font-weight: 800; color: var(--brown); font-size: 13.5px; }
        .mobile-item-bot {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mobile-item-weight {
            background: var(--cream); border: 1px solid var(--border); color: var(--text);
            font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
        }
        .mobile-item-subtotal { color: var(--muted); font-weight: 600; font-size: 12.5px; }
        .mobile-item-reason {
            font-size: 11px;
            color: var(--muted);
            font-style: italic;
            margin-top: 2px;
        }

        .mobile-total-row {
            padding: 12px 14px;
            background: var(--cream);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mobile-total-label { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .mobile-total-val { font-size: 16px; font-weight: 800; color: var(--brown); letter-spacing: -0.02em; }

        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
        }
    </style>

    <a href="{{ route('sales.returns.index') }}" class="sales-back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Daftar Return
    </a>

    @php
        $statusMap = [
            'menunggu' => ['badge-pending', 'Menunggu Verifikasi'],
            'diterima' => ['badge-approved', 'Diterima'],
            'ditolak'  => ['badge-canceled', 'Ditolak'],
        ];
        [$badgeCls, $badgeLbl] = $statusMap[$return->status] ?? ['badge-pending', $return->status];
    @endphp

    <div class="return-heading">
        <span class="return-number">{{ $return->return_number }}</span>
        <span class="badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
    </div>

    {{-- Info Card --}}
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:12.5px;color:#1e40af;line-height:1.5; display:flex; align-items:flex-start; gap:10px;">
        <i data-lucide="info" style="width:18px;height:18px;color:#1e40af;flex-shrink:0;margin-top:1px;"></i>
        <div>
            <strong>Status Return:</strong> Return barang yang dikonfirmasi oleh Admin akan otomatis memotong total sisa tagihan dari laporan pengiriman terkait.
        </div>
    </div>

    <div class="layout">
        {{-- Kiri: Item yang direturn --}}
        <div class="card">
            <div class="card-header">
                <h3>Item yang Dikembalikan</h3>
            </div>
            
            @if($return->items->isNotEmpty())
            <div style="padding: 14px 18px 0; font-weight: 700; font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;">
                Produk Satuan
            </div>
            <div class="table-scroll-container desktop-only">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center;">Qty Return</th>
                            <th style="text-align:right;">Harga/pcs</th>
                            <th style="text-align:right;">Subtotal</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($return->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:700;color:var(--text);font-size:13.5px;">{{ $item->product->name }}</div>
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">Kemasan: {{ $item->product->weight }} Gram</div>
                            </td>
                            <td style="text-align:center;font-weight:700;color:var(--text);">{{ number_format($item->qty_return, 0, ',', '.') }} pcs</td>
                            <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:800;color:var(--text);">Rp {{ number_format($item->subtotal_return, 0, ',', '.') }}</td>
                            <td style="color:var(--muted);font-size:12.5px;">{{ $item->reason ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-only">
                @foreach($return->items as $item)
                <div class="mobile-item">
                    <div class="mobile-item-top">
                        <div class="mobile-item-title">{{ $item->product->name }}</div>
                        <div class="mobile-item-qty">{{ number_format($item->qty_return, 0, ',', '.') }} pcs</div>
                    </div>
                    <div class="mobile-item-bot">
                        <span class="mobile-item-weight">{{ $item->product->weight }} Gram</span>
                        <span class="mobile-item-subtotal">Rp {{ number_format($item->subtotal_return, 0, ',', '.') }}</span>
                    </div>
                    @if($item->reason)
                    <div class="mobile-item-reason">Alasan: {{ $item->reason }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($return->packageItems->isNotEmpty())
            <div style="padding: 16px 18px 0; font-weight: 700; font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; {{ $return->items->isNotEmpty() ? 'border-top: 1px solid var(--border);' : '' }}">
                Paket / Pack
            </div>
            <div class="table-scroll-container desktop-only">
                <table>
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th style="text-align:center;">Qty Return</th>
                            <th style="text-align:right;">Harga/pack</th>
                            <th style="text-align:right;">Subtotal</th>
                            <th>Kondisi</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($return->packageItems as $item)
                        <tr>
                            <td>
                                <div style="font-weight:700;color:var(--text);font-size:13.5px;">{{ $item->package_name_snapshot }}</div>
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">Kode: {{ $item->package_code_snapshot }}</div>
                            </td>
                            <td style="text-align:center;font-weight:700;color:var(--text);">{{ number_format($item->qty, 0, ',', '.') }} pack</td>
                            <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:800;color:var(--text);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>
                                @if($item->condition === 'layak_jual')
                                    <span class="badge badge-approved" style="font-size:10px; padding:2px 6px;">Layak Jual</span>
                                @elseif($item->condition === 'tidak_layak_jual')
                                    <span class="badge badge-canceled" style="font-size:10px; padding:2px 6px;">Tidak Layak Jual</span>
                                @else
                                    <span class="badge badge-pending" style="font-size:10px; padding:2px 6px;">Perlu Proses Ulang</span>
                                @endif
                            </td>
                            <td style="color:var(--muted);font-size:12.5px;">{{ $item->replacement_note ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mobile-only">
                @foreach($return->packageItems as $item)
                <div class="mobile-item">
                    <div class="mobile-item-top">
                        <div class="mobile-item-title">{{ $item->package_name_snapshot }}</div>
                        <div class="mobile-item-qty">{{ number_format($item->qty, 0, ',', '.') }} pack</div>
                    </div>
                    <div class="mobile-item-bot" style="margin-top: 4px;">
                        <span class="mobile-item-weight" style="font-size: 11px;">
                            @if($item->condition === 'layak_jual')
                                Layak Jual
                            @elseif($item->condition === 'tidak_layak_jual')
                                Tidak Layak Jual
                            @else
                                Perlu Proses Ulang
                            @endif
                        </span>
                        <span class="mobile-item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($item->replacement_note)
                    <div class="mobile-item-reason">Catatan: {{ $item->replacement_note }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <div class="desktop-only" style="background:var(--cream); padding: 14px 18px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size:11.5px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;">Total Nilai Return</span>
                <span style="font-size:16px;font-weight:800;color:var(--brown);">
                    Rp {{ number_format($return->total_return, 0, ',', '.') }}
                </span>
            </div>
            <div class="mobile-only" style="border-top: 1px solid var(--border);">
                <div class="mobile-total-row">
                    <div class="mobile-total-label">Total Return</div>
                    <div class="mobile-total-val">Rp {{ number_format($return->total_return, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Kanan: Info return --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card">
                <div class="card-header">
                    <h3>Info Return</h3>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Laporan</span>
                    <span class="info-value" style="font-family:monospace;font-size:13px;">{{ $return->deliveryReport->report_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tgl Return</span>
                    <span class="info-value">{{ $return->return_date->format('d M Y') }}</span>
                </div>
                @if($return->status === 'diterima')
                <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
                    <span class="info-label">Kondisi Barang</span>
                    <span class="info-value" style="width:100%; text-align:left; margin-top:2px;">
                        @if($return->return_condition === 'layak_jual')
                            <span class="badge badge-approved" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check-circle" style="width:12px;height:12px;"></i> Layak Jual</span>
                        @elseif($return->return_condition === 'perlu_proses_ulang')
                            <span class="badge badge-pending" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="refresh-cw" style="width:12px;height:12px;"></i> Proses Ulang</span>
                        @else
                            <span style="color:var(--muted); font-style:italic; font-size:12.5px;">Belum Diisi</span>
                        @endif
                    </span>
                </div>
                @endif
                @if($return->note)
                <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
                    <span class="info-label">Catatan</span>
                    <span class="info-value" style="width:100%; text-align:left; font-weight:normal; font-style:italic; color:var(--muted); margin-top:2px;">
                        "{{ $return->note }}"
                    </span>
                </div>
                @endif
                @if($return->status === 'ditolak' && $return->rejection_reason)
                <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:4px; background:#fff5f5;">
                    <span class="info-label" style="color:#991b1b;">Alasan Ditolak</span>
                    <span class="info-value" style="width:100%; text-align:left; font-weight:600; color:#991b1b; margin-top:2px;">
                        {{ $return->rejection_reason }}
                    </span>
                </div>
                @endif
            </div>

            @if($return->approver)
                <div class="card">
                    <div class="card-header">
                        <h3>Diverifikasi Oleh</h3>
                    </div>
                    <div style="padding:16px; font-size:13px;">
                        <div style="font-weight:700; color:var(--brown);">{{ $return->approver->name }}</div>
                        <div style="font-size:11.5px; color:var(--muted); margin-top:2px;">
                            Pada {{ $return->approved_at?->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('sales.delivery-reports.show', $return->deliveryReport) }}" class="btn-secondary">
                <i data-lucide="file-text" style="width:14px;height:14px;"></i> Laporan Pengiriman
            </a>
        </div>
    </div>
</x-layouts.user>

<x-layouts.user>
    <x-slot name="title">Detail Pengajuan {{ $order->order_number }}</x-slot>

    <style>
        /* ── Page heading ──────────────────── */
        .order-heading { display:flex;align-items:center;gap:12px;margin-bottom:22px;flex-wrap:wrap; }
        .order-number  { font-size:17px;font-weight:700;color:var(--text);font-family:monospace;letter-spacing:0.02em; }
        
        .badge { display:inline-block;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;line-height:1.3;text-transform:uppercase;letter-spacing:0.04em; }
        .badge-pending  { background:#fffbeb;color:#b45309;border:1px solid #fef3c7; }
        .badge-approved { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
        .badge-done     { background:#eff6ff;color:#1d4ed8;border:1px solid #dbeafe; }
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

        /* ── Info panel ──────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:12px 18px;border-bottom:1px solid var(--border);font-size:13.5px; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em; }
        .info-value { font-size:13.5px;font-weight:600;color:var(--text);text-align:right;max-width:58%; }

        /* ── Timeline ────────────────────── */
        .timeline { padding:14px 16px;display:flex;flex-direction:column;gap:12px; }
        .tl-row { display:flex;gap:10px;align-items:flex-start; }
        .tl-dot {
            width:9px;height:9px;border-radius:50%;
            background:var(--border);flex-shrink:0;margin-top:3px;
            transition:background 0.2s;
        }
        .tl-dot.done { background:#16a34a; }
        .tl-text { flex:1; }
        .tl-label { font-size:12.5px;color:var(--text);font-weight:600; }
        .tl-time  { font-size:11.5px;color:var(--muted);margin-top:1px; }

        .note-box { padding:12px 18px;background:var(--cream);border-top:1px solid var(--border);font-size:12.5px;color:var(--muted);font-style:italic; }

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

    <a href="{{ route('sales.orders.index') }}" class="sales-back-link">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kembali ke Riwayat
    </a>

    @php
        $statusMap = [
            'menunggu'   => ['badge-pending',  'Menunggu Persetujuan'],
            'diproses'   => ['badge-approved', 'Disetujui'],
            'selesai'    => ['badge-done',     'Selesai'],
            'dibatalkan' => ['badge-canceled', 'Dibatalkan'],
        ];
        [$badgeCls, $badgeLbl] = $statusMap[$order->status] ?? ['badge-pending', $order->status];
    @endphp

    <div class="order-heading">
        <span class="order-number">{{ $order->order_number }}</span>
        <span class="badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
    </div>

    <div class="layout">
        {{-- Kiri: Daftar Produk & Paket --}}
        <div class="card">
            @if($order->items->isNotEmpty())
                <div class="card-header">
                    <h3>Daftar Produk Satuan yang Diminta</h3>
                </div>
                <div class="table-scroll-container desktop-only">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kemasan</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="font-weight:700;color:var(--text);">{{ $item->product->name }}</td>
                                <td>
                                    <span style="background:var(--cream);border:1px solid var(--border);color:var(--text);font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;">
                                        {{ $item->product->weight }} Gram
                                    </span>
                                </td>
                                <td style="text-align:center;font-weight:700;color:var(--text);">{{ $item->qty }} pcs</td>
                                <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mobile-only">
                    @foreach($order->items as $item)
                    <div class="mobile-item">
                        <div class="mobile-item-top">
                            <div class="mobile-item-title">{{ $item->product->name }}</div>
                            <div class="mobile-item-qty">{{ $item->qty }} pcs</div>
                        </div>
                        <div class="mobile-item-bot">
                            <span class="mobile-item-weight">{{ $item->product->weight }} Gram</span>
                            <span class="mobile-item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            @if($order->packageItems->isNotEmpty())
                <div class="card-header" style="border-top: {{ $order->items->isNotEmpty() ? '1px solid var(--border)' : 'none' }};">
                    <h3>Daftar Paket / Pack yang Diminta</h3>
                </div>
                <div class="table-scroll-container desktop-only">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Kode Paket</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->packageItems as $item)
                            <tr>
                                <td style="font-weight:700;color:var(--text);">{{ $item->package->name }}</td>
                                <td>
                                    <code>{{ $item->package->code }}</code>
                                </td>
                                <td style="text-align:center;font-weight:700;color:var(--text);">{{ number_format($item->qty, 0, ',', '.') }} pack</td>
                                <td style="text-align:right;color:var(--muted);font-weight:500;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mobile-only">
                    @foreach($order->packageItems as $item)
                    <div class="mobile-item">
                        <div class="mobile-item-top">
                            <div class="mobile-item-title">{{ $item->package->name }}</div>
                            <div class="mobile-item-qty">{{ number_format($item->qty, 0, ',', '.') }} pack</div>
                        </div>
                        <div class="mobile-item-bot">
                            <span class="mobile-item-weight"><code>{{ $item->package->code }}</code></span>
                            <span class="mobile-item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

            <div class="mobile-total-row">
                <div class="mobile-total-label">Total Estimasi</div>
                <div class="mobile-total-val">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            </div>

            @if($order->catatan)
            <div class="note-box">"{{ $order->catatan }}"</div>
            @endif
        </div>

        {{-- Kanan: Info & Status --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Pengajuan</h3>
                </div>
                <div class="info-row">
                    <span class="info-label">Tujuan</span>
                    <span class="info-value">{{ $order->customer->name ?? 'Stok Sales' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Diajukan</span>
                    <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Status Persetujuan</h3>
                </div>
                <div class="timeline">
                    <div class="tl-row">
                        <div class="tl-dot done"></div>
                        <div class="tl-text">
                            <div class="tl-label">Pengajuan dikirim</div>
                            <div class="tl-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    <div class="tl-row">
                        <div class="tl-dot {{ $order->processed_at ? 'done' : '' }}"></div>
                        <div class="tl-text">
                            <div class="tl-label">Disetujui admin</div>
                            <div class="tl-time">{{ $order->processed_at ? $order->processed_at->format('d M Y, H:i') : 'Menunggu persetujuan...' }}</div>
                        </div>
                    </div>
                    <div class="tl-row">
                        <div class="tl-dot {{ $order->status === 'selesai' ? 'done' : '' }}"></div>
                        <div class="tl-text">
                            <div class="tl-label">Barang siap diambil</div>
                            <div class="tl-time">{{ $order->status === 'selesai' ? 'Selesai' : '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.user>

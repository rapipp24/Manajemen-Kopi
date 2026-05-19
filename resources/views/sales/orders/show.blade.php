<x-layouts.user>
    <x-slot name="title">Detail Pengajuan {{ $order->order_number }}</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:18px; }
        .back-link:hover { color:#1c1917; }

        /* ── Page heading ──────────────────── */
        .order-heading { display:flex;align-items:center;gap:10px;margin-bottom:22px;flex-wrap:wrap; }
        .order-number  { font-size:17px;font-weight:700;color:#1c1917;font-family:monospace;letter-spacing:0.02em; }
        .badge { display:inline-block;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700; }
        .badge-pending  { background:#fef3c7;color:#92400e; }
        .badge-approved { background:#f0fdf4;color:#16a34a; }
        .badge-done     { background:#eff6ff;color:#1d4ed8; }
        .badge-canceled { background:#fef2f2;color:#dc2626; }

        /* ── Layout ──────────────────────── */
        .layout { display:grid;grid-template-columns:1fr 260px;gap:16px;align-items:start; }

        /* ── Card ────────────────────────── */
        .card { background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden; }
        .card-header { padding:13px 18px;border-bottom:1px solid #f5f0eb;background:#fafaf8; }
        .card-header h3 { font-size:13px;font-weight:700;color:#1c1917;margin:0; }

        /* ── Table ───────────────────────── */
        table { width:100%;border-collapse:collapse; }
        th { padding:9px 18px;font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid #f5f0eb;text-align:left; }
        td { padding:13px 18px;border-bottom:1px solid #f5f0eb;font-size:13px; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fdfcfb; }

        /* ── Info panel ──────────────────── */
        .info-row { display:flex;justify-content:space-between;align-items:baseline;padding:11px 16px;border-bottom:1px solid #f5f0eb; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.05em; }
        .info-value { font-size:13px;font-weight:600;color:#1c1917;text-align:right;max-width:58%; }

        /* ── Timeline ────────────────────── */
        .timeline { padding:14px 16px;display:flex;flex-direction:column;gap:12px; }
        .tl-row { display:flex;gap:10px;align-items:flex-start; }
        .tl-dot {
            width:9px;height:9px;border-radius:50%;
            background:#e7e5e4;flex-shrink:0;margin-top:3px;
            transition:background 0.2s;
        }
        .tl-dot.done { background:#16a34a; }
        .tl-text { flex:1; }
        .tl-label { font-size:12.5px;color:#44403c;font-weight:500; }
        .tl-time  { font-size:11.5px;color:#a8a29e;margin-top:1px; }

        /* ── Note box ────────────────────── */
        .note-box { padding:12px 18px;background:#fafaf8;border-top:1px solid #f5f0eb;font-size:12.5px;color:#78716c;font-style:italic; }

        /* ── Responsive ──────────────────── */
        @media (max-width:680px) { .layout { grid-template-columns:1fr; } }
    </style>

    <a href="{{ route('sales.orders.index') }}" class="back-link">← Kembali ke Riwayat</a>

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

        {{-- Kiri: Daftar Produk --}}
        <div class="card">
            <div class="card-header"><h3>Daftar Barang yang Diminta</h3></div>
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
                        <td style="font-weight:600;color:#1c1917;">{{ $item->product->name }}</td>
                        <td>
                            <span style="background:#fdf3e7;border:1px solid #f0d9b5;color:#7a5c3e;font-size:11.5px;font-weight:600;padding:2px 8px;border-radius:20px;">
                                {{ $item->product->weight }} Gram
                            </span>
                        </td>
                        <td style="text-align:center;font-weight:700;color:#1c1917;">{{ $item->qty }}</td>
                        <td style="text-align:right;color:#78716c;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;font-size:10.5px;font-weight:700;color:#b9a99a;text-transform:uppercase;padding-top:12px;letter-spacing:0.06em;">
                            Total Estimasi
                        </td>
                        <td style="text-align:right;font-size:17px;font-weight:800;color:#92400e;padding-top:12px;letter-spacing:-0.02em;">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
            @if($order->catatan)
            <div class="note-box">"{{ $order->catatan }}"</div>
            @endif
        </div>

        {{-- Kanan: Info & Status --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            <div class="card">
                <div class="card-header"><h3>Informasi Pengajuan</h3></div>
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
                <div class="card-header"><h3>Status Persetujuan</h3></div>
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

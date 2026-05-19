<x-layouts.user>
    <x-slot name="title">Detail Pengajuan {{ $order->order_number }}</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:20px; }
        .back-link:hover { color:#1c1917; }
        .layout { display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start; }
        .card { background:#fff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden; }
        .card-header { padding:16px 20px;border-bottom:1px solid #f5f5f4;background:#fafaf9; }
        .card-header h3 { font-size:14px;font-weight:700;color:#1c1917;margin:0; }
        .card-body { padding:20px; }
        table { width:100%;border-collapse:collapse; }
        th { padding:10px 0;font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;border-bottom:1px solid #f5f5f4; }
        td { padding:14px 0;border-bottom:1px solid #f5f5f4;font-size:13.5px; }
        tr:last-child td { border-bottom:none; }
        .badge { display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700; }
        .badge-pending  { background:#fef3c7;color:#92400e; }
        .badge-approved { background:#f0fdf4;color:#166534; }
        .badge-done     { background:#e0f2fe;color:#075985; }
        .badge-canceled { background:#fef2f2;color:#991b1b; }

        .info-row { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; }
        .info-row:last-child { margin-bottom:0; }
        .info-label { font-size:11px; font-weight:700; color:#a8a29e; text-transform:uppercase; letter-spacing:0.04em; }
        .info-value { font-size:13px; font-weight:600; color:#1c1917; text-align:right; max-width:65%; }
        .log-item { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; font-size:12px; color:#78716c; }
        .log-item:last-child { margin-bottom:0; }
        .log-item strong { color:#1c1917; text-align:right; }
    </style>

    <a href="{{ route('sales.orders.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
    </a>

    @php
        $statusMap = [
            'menunggu'   => ['badge-pending',  'Menunggu Persetujuan'],
            'diproses'   => ['badge-approved', 'Disetujui'],
            'selesai'    => ['badge-done',     'Selesai Diambil'],
            'dibatalkan' => ['badge-canceled', 'Ditolak / Dibatalkan'],
        ];
        [$badgeCls, $badgeLbl] = $statusMap[$order->status] ?? ['badge-pending', $order->status];
    @endphp

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <h1 style="font-size:18px;font-weight:700;color:#1c1917;font-family:monospace;">{{ $order->order_number }}</h1>
        <span class="badge {{ $badgeCls }}">{{ $badgeLbl }}</span>
    </div>

    <div class="layout">
        {{-- Kiri: Tabel Item --}}
        <div class="card">
            <div class="card-header"><h3>Daftar Barang yang Diminta</h3></div>
            <div style="padding:0 20px;">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align:left;">Produk</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td style="font-weight:600;color:#1c1917;">{{ $item->product->name }} — {{ $item->product->weight }}gr</td>
                            <td style="text-align:center;font-weight:700;">{{ $item->qty }}</td>
                            <td style="text-align:right;color:#78716c;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align:right;font-size:12px;font-weight:700;color:#a8a29e;text-transform:uppercase;padding-top:12px;">Total Nilai</td>
                            <td style="text-align:right;font-size:18px;font-weight:800;color:#92400e;padding-top:12px;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($order->catatan)
            <div style="padding:14px 20px;background:#fafaf9;border-top:1px solid #f5f5f4;font-size:13px;color:#78716c;font-style:italic;">
                "{{ $order->catatan }}"
            </div>
            @endif
        </div>

        {{-- Kanan: Info --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card">
                <div class="card-header"><h3>Info Pengajuan</h3></div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Tujuan / Toko</div>
                        <div class="info-value">{{ $order->customer->name ?? 'Stok Sales' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Diajukan</div>
                        <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3>Log Persetujuan</h3></div>
                <div class="card-body">
                    <div class="log-item">
                        <span>Diajukan</span>
                        <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    <div class="log-item">
                        <span>Disetujui Admin</span>
                        <strong>{{ $order->processed_at ? $order->processed_at->format('d/m/Y H:i') : '—' }}</strong>
                    </div>
                    <div class="log-item">
                        <span>Stok Gudang</span>
                        <strong style="color:{{ $order->processed_at ? '#166534' : '#92400e' }};">
                            {{ $order->processed_at ? 'Sudah terpotong' : 'Menunggu admin' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.user>

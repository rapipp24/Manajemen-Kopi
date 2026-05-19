<x-layouts.user>
    <x-slot name="title">Laporan {{ $deliveryReport->report_number }}</x-slot>

    <style>
        .back-link { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:20px; }
        .back-link:hover { color:#1c1917; }
        .layout { display:grid;grid-template-columns:1fr 260px;gap:20px;align-items:start; }
        .card { background:#fff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden; }
        .card-header { padding:14px 18px;border-bottom:1px solid #f5f5f4;background:#fafaf9; }
        .card-header h3 { font-size:13.5px;font-weight:700;color:#1c1917;margin:0; }
        table { width:100%;border-collapse:collapse; }
        th { padding:10px 18px;font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;background:#fafaf9;border-bottom:1px solid #f5f5f4;text-align:left; }
        td { padding:13px 18px;border-bottom:1px solid #f5f5f4;font-size:13.5px; }
        tr:last-child td { border-bottom:none; }
        .info-row { padding:12px 18px;border-bottom:1px solid #f5f5f4;display:flex;justify-content:space-between;align-items:baseline; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase; }
        .info-value { font-size:13.5px;font-weight:600;color:#1c1917;text-align:right;max-width:60%; }
    </style>

    <a href="{{ route('sales.delivery-reports.index') }}" class="back-link">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
    </a>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
        <h1 style="font-size:18px;font-weight:700;color:#1c1917;font-family:monospace;">{{ $deliveryReport->report_number }}</h1>
        <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">✓ Terkirim</span>
    </div>

    <div class="layout">
        {{-- Kiri: Tabel Produk --}}
        <div class="card">
            <div class="card-header"><h3>Produk yang Dikirim</h3></div>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Harga</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveryReport->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:#1c1917;">{{ $item->product->name }}</div>
                            <div style="font-size:11px;color:#a8a29e;">Kemasan: {{ $item->product->weight }}gr</div>
                        </td>
                        <td style="text-align:center;font-weight:700;">{{ number_format($item->qty, 0, ',', '.') }}</td>
                        <td style="text-align:right;color:#78716c;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align:right;font-weight:700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;padding-top:12px;">Total Nilai</td>
                        <td style="text-align:right;font-size:18px;font-weight:800;color:#92400e;padding-top:12px;">Rp {{ number_format($deliveryReport->items->sum('subtotal'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Kanan: Info --}}
        <div class="card">
            <div class="card-header"><h3>Info Pengiriman</h3></div>
            <div class="info-row">
                <span class="info-label">Toko Tujuan</span>
                <span class="info-value">{{ $deliveryReport->toko_name }}</span>
            </div>
            @if($deliveryReport->customer_address_manual)
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value" style="font-size:12px;">{{ $deliveryReport->customer_address_manual }}</span>
            </div>
            @endif
            @if($deliveryReport->customer_phone_manual)
            <div class="info-row">
                <span class="info-label">No. HP</span>
                <span class="info-value">{{ $deliveryReport->customer_phone_manual }}</span>
            </div>
            @endif
            @if($deliveryReport->payment_term_days)
            <div class="info-row">
                <span class="info-label">Tempo</span>
                <span class="info-value" style="color:#92400e;">{{ $deliveryReport->payment_term_days }} hari</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Tanggal Kirim</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($deliveryReport->delivery_date)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dicatat</span>
                <span class="info-value" style="font-size:12px;">{{ $deliveryReport->created_at->format('d M Y, H:i') }}</span>
            </div>
            @if($deliveryReport->note)
            <div style="padding:12px 18px;background:#fafaf9;border-top:1px solid #f5f5f4;">
                <div style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;margin-bottom:4px;">Catatan</div>
                <div style="font-size:13px;color:#78716c;font-style:italic;">{{ $deliveryReport->note }}</div>
            </div>
            @endif
        </div>
    </div>

</x-layouts.user>

<x-layouts.user>
    <x-slot name="title">Riwayat Pengiriman</x-slot>

    <style>
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px; }
        .page-title { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.02em; }
        .page-desc { font-size:13px;color:#78716c;margin-top:4px; }
        .btn-primary { background:#92400e;color:white;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;transition:background 0.15s; }
        .btn-primary:hover { background:#78350f; }

        .stok-banner { background:#fff;border:1px solid #e7e5e4;border-radius:10px;padding:14px 18px;margin-bottom:20px; }
        .stok-banner-title { font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px; }
        .stok-chips { display:flex;flex-wrap:wrap;gap:8px; }
        .stok-chip { background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700; }
        .stok-chip.empty { background:#f5f5f4;color:#a8a29e; }

        .table-card { background:#fff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden; }
        table { width:100%;border-collapse:collapse; }
        thead tr { background:#fafaf9;border-bottom:1px solid #e7e5e4; }
        th { padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;letter-spacing:0.05em; }
        td { padding:13px 16px;border-bottom:1px solid #f5f5f4;font-size:13.5px;color:#44403c; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fafaf9; }
        .btn-link { font-size:13px;font-weight:600;color:#92400e;text-decoration:none; }
        .btn-link:hover { text-decoration:underline; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengiriman</h1>
            <p class="page-desc">Laporan barang yang sudah Anda kirimkan ke toko.</p>
        </div>
        <a href="{{ route('sales.delivery-reports.create') }}" class="btn-primary">
            <i data-lucide="send" style="width:14px;height:14px;"></i> Buat Laporan Kirim
        </a>
    </div>

    {{-- Stok Sales saat ini --}}
    @php
        $myStocks = \App\Models\SalesStock::with('product.unit')
            ->where('user_id', auth()->id())
            ->where('qty', '>', 0)
            ->get();
    @endphp
    <div class="stok-banner">
        <div class="stok-banner-title">Stok Barang Anda Saat Ini</div>
        <div class="stok-chips">
            @forelse($myStocks as $s)
                <span class="stok-chip">{{ $s->product->name }}: {{ number_format($s->qty, 0, ',', '.') }} {{ $s->product->unit->code ?? '' }}</span>
            @empty
                <span class="stok-chip empty">Belum ada stok — ajukan barang ke gudang</span>
            @endforelse
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>No. Laporan</th>
                    <th>Toko Tujuan</th>
                    <th>Tanggal Kirim</th>
                    <th>Dibuat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                <tr>
                    <td style="font-family:monospace;font-weight:700;color:#1c1917;">{{ $r->report_number }}</td>
                    <td style="font-weight:600;">{{ $r->toko_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->delivery_date)->format('d M Y') }}</td>
                    <td style="color:#78716c;">{{ $r->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('sales.delivery-reports.show', $r) }}" class="btn-link">Lihat →</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:56px;text-align:center;color:#a8a29e;">
                        <i data-lucide="truck" style="width:36px;height:36px;margin:0 auto 12px;display:block;opacity:0.3;"></i>
                        <div style="font-size:14px;font-weight:600;color:#78716c;margin-bottom:4px;">Belum ada laporan pengiriman</div>
                        <a href="{{ route('sales.delivery-reports.create') }}" style="font-size:13px;color:#92400e;font-weight:600;text-decoration:none;">Buat Laporan Pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div style="padding:12px 16px;border-top:1px solid #f5f5f4;">{{ $reports->links() }}</div>
        @endif
    </div>

</x-layouts.user>

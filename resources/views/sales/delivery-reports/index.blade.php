<x-layouts.user>
    <x-slot name="title">Riwayat Pengiriman</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px; }
        .page-title  { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.03em; }
        .page-desc   { font-size:13px;color:#78716c;margin-top:3px; }

        .btn-primary {
            background:#92400e;color:#fff;padding:9px 18px;border-radius:9px;
            text-decoration:none;font-size:13px;font-weight:600;
            display:inline-flex;align-items:center;gap:7px;
            transition:background 0.15s,box-shadow 0.15s;white-space:nowrap;
            box-shadow:0 1px 3px rgba(146,64,14,0.25);
        }
        .btn-primary:hover { background:#78350f;box-shadow:0 3px 8px rgba(146,64,14,0.3); }

        /* ── Stok Card ───────────────────────── */
        .stok-card {
            background:#fff;border:1px solid #ece8e3;border-radius:12px;
            padding:16px 20px;margin-bottom:18px;
        }
        .stok-card-header {
            display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;
        }
        .stok-card-title { font-size:12px;font-weight:700;color:#57534e;text-transform:uppercase;letter-spacing:0.07em; }
        .stok-ajukan { font-size:12px;color:#92400e;font-weight:600;text-decoration:none; }
        .stok-ajukan:hover { text-decoration:underline; }

        .stok-list { display:flex;flex-wrap:wrap;gap:8px; }

        /* Each stock item as a small card */
        .stok-item {
            display:flex;align-items:center;gap:10px;
            background:#fdf9f5;border:1px solid #ece8e3;border-radius:8px;
            padding:8px 14px;min-width:180px;
        }
        .stok-item-qty  { font-size:18px;font-weight:800;color:#92400e;letter-spacing:-0.02em;line-height:1; }
        .stok-item-info { display:flex;flex-direction:column;gap:1px; }
        .stok-item-name { font-size:12.5px;font-weight:600;color:#1c1917;line-height:1.2; }
        .stok-item-unit { font-size:11px;color:#78716c;font-weight:500; }

        .stok-empty-note {
            font-size:13px;color:#a8a29e;
            padding:10px 0 2px;
        }

        /* ── Table Card ──────────────────────── */
        .table-card { background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden; }
        .table-title { padding:14px 18px;border-bottom:1px solid #f5f0eb;font-size:13px;font-weight:700;color:#1c1917; }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:#fafaf8;border-bottom:1px solid #ece8e3; }
        th { padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em; }
        td { padding:13px 16px;border-bottom:1px solid #f5f0eb;font-size:13px;color:#44403c;vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fdfcfb; }

        .btn-link { font-size:12.5px;font-weight:600;color:#92400e;text-decoration:none; }
        .btn-link:hover { text-decoration:underline; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { padding:56px 20px;text-align:center; }
        .empty-emoji { font-size:34px;margin-bottom:10px;opacity:0.25; }
        .empty-title { font-size:14px;font-weight:600;color:#78716c;margin-bottom:8px; }
        .empty-cta { font-size:13px;color:#92400e;font-weight:600;text-decoration:none; }
        .empty-cta:hover { text-decoration:underline; }

        /* ── Responsive ──────────────────────── */
        @media (max-width:640px) {
            .stok-item { min-width:140px; }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengiriman</h1>
            <p class="page-desc">Laporan barang yang sudah Anda kirimkan ke toko.</p>
        </div>
        <a href="{{ route('sales.delivery-reports.create') }}" class="btn-primary">
            + Buat Laporan Kirim
        </a>
    </div>

    {{-- ── Stok Barang Saat Ini ──────────────── --}}
    @php
        $myStocks = \App\Models\SalesStock::with('product.unit')
            ->where('user_id', auth()->id())
            ->where('qty', '>', 0)
            ->get();
    @endphp

    <div class="stok-card">
        <div class="stok-card-header">
            <span class="stok-card-title">Stok Barang Anda Saat Ini</span>
            @if($myStocks->isEmpty())
                <a href="{{ route('sales.orders.create') }}" class="stok-ajukan">+ Ajukan Barang →</a>
            @endif
        </div>

        @if($myStocks->isEmpty())
            <p class="stok-empty-note">Anda belum memiliki stok barang. Ajukan barang ke gudang terlebih dahulu.</p>
        @else
            <div class="stok-list">
                @foreach($myStocks as $s)
                <div class="stok-item">
                    <div class="stok-item-qty">{{ number_format($s->qty, 0, ',', '.') }}</div>
                    <div class="stok-item-info">
                        <div class="stok-item-name">{{ $s->product->name }}</div>
                        <div class="stok-item-unit">{{ $s->product->weight }} Gram · {{ $s->product->unit->code ?? '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── Tabel Riwayat ──────────────────────── --}}
    <div class="table-card">
        <div class="table-title">Semua Laporan Pengiriman</div>
        <table>
            <thead>
                <tr>
                    <th>No. Laporan</th>
                    <th>Toko Tujuan</th>
                    <th>Status</th>
                    <th>Tanggal Kirim</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:#1c1917;font-size:12px;">{{ $r->report_number }}</span>
                    </td>
                    <td style="font-weight:600;">{{ $r->toko_name }}</td>
                    <td>
                        @if($r->payment_status === 'lunas')
                            <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">Lunas</span>
                        @elseif($r->payment_status === 'dp')
                            <span style="background:#fef08a;color:#854d0e;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">DP</span>
                        @else
                            <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:600;padding:4px 8px;border-radius:6px;">Belum Bayar</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->delivery_date)->format('d M Y') }}</td>
                    <td><a href="{{ route('sales.delivery-reports.show', $r) }}" class="btn-link">Lihat →</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-wrap">
                            <div class="empty-emoji">🚚</div>
                            <div class="empty-title">Belum ada laporan pengiriman</div>
                            <a href="{{ route('sales.delivery-reports.create') }}" class="empty-cta">Buat Laporan Pertama →</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div style="padding:10px 16px;border-top:1px solid #f5f0eb;">{{ $reports->links() }}</div>
        @endif
    </div>

</x-layouts.user>

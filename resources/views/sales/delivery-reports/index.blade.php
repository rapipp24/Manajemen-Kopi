<x-layouts.user>
    <x-slot name="title">Riwayat Pengiriman</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }
        .page-desc   { font-size:13.5px;color:var(--muted);margin-top:4px; }

        .btn-primary {
            background:var(--brown);color:#fff;padding:9.5px 18px;border-radius:8px;
            text-decoration:none;font-size:13px;font-weight:700;
            display:inline-flex;align-items:center;gap:8px;
            transition:all 0.15s ease-in-out;white-space:nowrap;
            box-shadow:0 2px 4px rgba(42,23,14,0.1);
        }
        .btn-primary:hover { background:var(--brown-hover);box-shadow:0 4px 12px rgba(42,23,14,0.15); }

        /* ── Stok Card ───────────────────────── */
        .stok-card {
            background:#fff;border:1px solid var(--border);border-radius:12px;
            padding:18px 20px;margin-bottom:20px;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .stok-card-header {
            display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;
        }
        .stok-card-title { font-size:12px;font-weight:800;color:var(--text);text-transform:uppercase;letter-spacing:0.07em; }
        .stok-ajukan { font-size:12.5px;color:var(--accent);font-weight:700;text-decoration:none; }
        .stok-ajukan:hover { text-decoration:underline; }

        .stok-list { display:flex;flex-wrap:wrap;gap:10px; }

        /* Each stock item */
        .stok-item {
            display:flex;align-items:center;gap:12px;
            background:var(--cream);border:1px solid var(--border);border-radius:8px;
            padding:10px 16px;min-width:180px;
        }
        .stok-item-qty  { font-size:20px;font-weight:800;color:var(--brown);letter-spacing:-0.02em;line-height:1; }
        .stok-item-info { display:flex;flex-direction:column;gap:1px; }
        .stok-item-name { font-size:13px;font-weight:700;color:var(--text);line-height:1.2; }
        .stok-item-unit { font-size:11px;color:var(--muted);font-weight:600; }

        .stok-empty-note {
            font-size:13.5px;color:var(--muted);
            padding:8px 0 2px;
        }

        /* ── Table Card ──────────────────────── */
        .table-card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .table-title { padding:14px 18px;border-bottom:1px solid var(--border);font-size:13.5px;font-weight:700;color:var(--text);background:var(--cream); }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:var(--cream);border-bottom:1px solid var(--border); }
        th { padding:12px 18px;text-align:left;font-size:10px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em; }
        td { padding:14px 18px;border-bottom:1px solid var(--border);font-size:13.5px;color:var(--text);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:var(--cream); }



        /* Status Pills */
        .badge-status {
            font-size:11px;font-weight:700;padding:4px 10px;border-radius:6px;text-transform:uppercase;letter-spacing:0.04em;
        }
        .badge-lunas { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
        .badge-dp { background:#fffbeb;color:#b45309;border:1px solid #fef3c7; }
        .badge-piutang { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { padding:56px 20px;text-align:center; background:#fff; border:1px solid var(--border); border-radius:12px; }
        .empty-emoji { font-size:36px;margin-bottom:10px;opacity:0.3; }
        .empty-title { font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px; }
        .empty-cta { font-size:13px;color:var(--accent);font-weight:700;text-decoration:none; }
        .empty-cta:hover { text-decoration:underline; }

        /* ── Desktop/Mobile Dual Layout ──────── */
        .desktop-only { display: block; }
        .mobile-only { display: none; }

        .mobile-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .mobile-card-num {
            font-family: monospace;
            font-weight: 700;
            font-size: 13px;
            color: var(--text);
        }
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
        }
        .mobile-card-label {
            color: var(--muted);
            font-weight: 500;
        }
        .mobile-card-val {
            font-weight: 600;
            color: var(--text);
        }
        .mobile-card-actions {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--border);
            text-align: right;
            opacity: 0.9;
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 767px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
            .stok-list {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 10px;
                padding-bottom: 8px;
            }
            .stok-item {
                flex-shrink: 0;
                min-width: 170px;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengiriman</h1>
            <p class="page-desc">Laporan barang yang sudah Anda kirimkan ke toko.</p>
        </div>
        <a href="{{ route('sales.delivery-reports.create') }}" class="btn-primary">
            <i data-lucide="plus-circle" style="width:16px;height:16px;"></i> Buat Laporan Kirim
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
                <a href="{{ route('sales.orders.create') }}" class="sales-action-pill">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Ajukan Barang
                </a>
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

    {{-- ── Tabel Riwayat: Desktop ──────────────── --}}
    <div class="table-card desktop-only">
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
                        <span style="font-family:monospace;font-weight:700;color:var(--text);font-size:12px;">{{ $r->report_number }}</span>
                    </td>
                    <td style="font-weight:600;">{{ $r->toko_name }}</td>
                    <td>
                        @if($r->payment_status === 'lunas')
                            <span class="badge-status badge-lunas">Lunas</span>
                        @elseif($r->payment_status === 'dp')
                            <span class="badge-status badge-dp">DP</span>
                        @else
                            <span class="badge-status badge-piutang">Belum Bayar</span>
                        @endif
                    </td>
                    <td style="color:var(--muted); font-weight: 500;">{{ \Carbon\Carbon::parse($r->delivery_date)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('sales.delivery-reports.show', $r) }}" class="sales-detail-link">
                            Lihat Detail <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-wrap" style="border:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; color: var(--muted); margin: 0 auto 12px; display: block;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <div class="empty-title">Belum ada laporan pengiriman</div>
                            <a href="{{ route('sales.delivery-reports.create') }}" class="sales-action-pill" style="margin-top: 8px;">
                                <i data-lucide="plus" style="width:14px;height:14px;"></i> Buat Laporan Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div style="padding:10px 16px;border-top:1px solid var(--border);">{{ $reports->links() }}</div>
        @endif
    </div>

    <!-- Mobile View (Card List) -->
    <div class="mobile-only">
        @forelse($reports as $r)
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <span class="mobile-card-num">{{ $r->report_number }}</span>
                    @if($r->payment_status === 'lunas')
                        <span class="badge-status badge-lunas">Lunas</span>
                    @elseif($r->payment_status === 'dp')
                        <span class="badge-status badge-dp">DP</span>
                    @else
                        <span class="badge-status badge-piutang">Belum Bayar</span>
                    @endif
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Toko:</span>
                    <span class="mobile-card-val">{{ $r->toko_name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Tanggal:</span>
                    <span class="mobile-card-val">{{ \Carbon\Carbon::parse($r->delivery_date)->format('d M Y') }}</span>
                </div>
                <div class="mobile-card-actions">
                    <a href="{{ route('sales.delivery-reports.show', $r) }}" class="sales-detail-link">
                        Lihat Detail <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; color: var(--muted); margin: 0 auto 12px; display: block;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                <div class="empty-title">Belum ada laporan pengiriman</div>
                <a href="{{ route('sales.delivery-reports.create') }}" class="sales-action-pill" style="margin-top: 8px;">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Buat Laporan Pertama
                </a>
            </div>
        @endforelse

        @if($reports->hasPages())
            <div style="margin-top:12px;">{{ $reports->links() }}</div>
        @endif
    </div>

</x-layouts.user>

<x-layouts.user>
    <x-slot name="title">Riwayat Pengiriman</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }
        .page-desc   { font-size:13.5px;color:var(--muted);margin-top:4px; }



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
        /* ── Empty State Premium ──────────────── */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 56px 24px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(42, 23, 14, 0.03);
            margin: 16px 0;
        }
        .empty-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.12) 0%, rgba(197, 160, 89, 0.02) 75%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid rgba(197, 160, 89, 0.15);
        }
        .empty-icon-circle i {
            color: var(--accent);
            width: 36px;
            height: 36px;
            stroke-width: 1.5;
        }
        .empty-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }
        .empty-desc {
            font-size: 13.5px;
            color: var(--muted);
            max-width: 320px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .empty-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--brown);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.15);
            min-height: 46px;
            cursor: pointer;
        }
        .empty-btn:hover {
            background: var(--brown-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(42, 23, 14, 0.2);
            color: #ffffff;
        }
        .empty-btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background:var(--brown);color:#fff;padding:10px 18px;border-radius:10px;
            text-decoration:none;font-size:13px;font-weight:700;
            display:inline-flex;align-items:center;gap:8px;
            transition:all 0.2s ease;white-space:nowrap;
            box-shadow:0 4px 12px rgba(42,23,14,0.12);
        }
        .btn-primary:hover { background:var(--brown-hover);box-shadow:0 6px 16px rgba(42,23,14,0.18); transform: translateY(-1px); }
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
        }

        /* ── Stok Tabs ───────────────────────── */
        .stok-tabs {
            display: inline-flex;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 4px;
            gap: 4px;
            margin-bottom: 20px;
        }
        .stok-tab-btn {
            background: none;
            border: none;
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .stok-tab-btn:hover {
            color: var(--text);
        }
        .stok-tab-btn.active {
            background: #ffffff;
            color: var(--brown);
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.08);
        }

        /* ── Stok Grid & Cards ────────────────── */
        .stok-meta-summary {
            font-size: 12.5px;
            color: var(--muted);
            font-weight: 500;
            margin-top: 2px;
        }
        .stok-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 12px;
        }
        .stok-card-item {
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        .stok-card-item:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.04);
            transform: translateY(-1px);
        }
        .stok-card-qty-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 68px;
            padding-right: 14px;
            border-right: 1.5px dashed var(--border);
            text-align: center;
        }
        .stok-card-qty {
            font-size: 24px;
            font-weight: 850;
            color: var(--brown);
            line-height: 1;
            letter-spacing: -0.02em;
        }
        .stok-card-qty-label {
            font-size: 9px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }
        .stok-card-info {
            flex: 1;
            padding-left: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .stok-card-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .stok-card-meta {
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
        }
        .stok-card-package {
            border-top: 2.5px solid var(--brown);
        }
        .stok-badge-package {
            display: inline-block;
            background: var(--brown-light);
            color: var(--brown);
            font-size: 9px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            align-self: flex-start;
        }
        .stok-card-items-list {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 767px) {
            .stok-grid {
                grid-template-columns: 1fr;
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

        $myPackageStocks = \App\Models\SalesPackageStock::with(['package.items.product.unit'])
            ->where('user_id', auth()->id())
            ->where('qty', '>', 0)
            ->get();

        $allEmpty = $myStocks->isEmpty() && $myPackageStocks->isEmpty();
    @endphp

    <div class="stok-card">
        <div class="stok-card-header" style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span class="stok-card-title">Stok Anda Saat Ini</span>
                @if(!$allEmpty)
                    <div class="stok-meta-summary">
                        Produk: <strong>{{ $myStocks->count() }} jenis</strong> · Paket: <strong>{{ $myPackageStocks->count() }} jenis</strong>
                    </div>
                @endif
            </div>
            @if($allEmpty)
                <a href="{{ route('sales.orders.create') }}" class="sales-action-pill">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Ajukan Barang
                </a>
            @endif
        </div>

        @if($allEmpty)
            <p class="stok-empty-note">Anda belum memiliki stok produk maupun paket. Ajukan barang/paket ke gudang terlebih dahulu.</p>
        @else
            <div class="stok-tabs">
                <button type="button" class="stok-tab-btn active" onclick="switchStokTab('tab-produk', event)">Stok Produk Satuan</button>
                <button type="button" class="stok-tab-btn" onclick="switchStokTab('tab-paket', event)">Stok Paket / Pack</button>
            </div>

            <!-- Tab Produk Satuan -->
            <div id="tab-produk" class="stok-tab-content">
                @if($myStocks->isEmpty())
                    <p class="stok-empty-note">Toko/sales belum memiliki stok produk satuan.</p>
                @else
                    <div class="stok-grid">
                        @foreach($myStocks as $s)
                        <div class="stok-card-item">
                            <div class="stok-card-qty-box">
                                <span class="stok-card-qty">{{ number_format($s->qty, 0, ',', '.') }}</span>
                                <span class="stok-card-qty-label">{{ $s->product->unit->code ?? 'Pcs' }}</span>
                            </div>
                            <div class="stok-card-info">
                                <span class="stok-card-name" title="{{ $s->product->name }}">{{ $s->product->name }}</span>
                                <span class="stok-card-meta">{{ $s->product->weight }} Gram</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tab Paket / Pack -->
            <div id="tab-paket" class="stok-tab-content" style="display: none;">
                @if($myPackageStocks->isEmpty())
                    <p class="stok-empty-note">Belum ada stok paket/pack yang dibawa.</p>
                @else
                    <div class="stok-grid">
                        @foreach($myPackageStocks as $ps)
                        @php
                            $itemsRingkas = $ps->package->items->map(function($item) {
                                $unitName = $item->product->unit->name ?? 'pcs';
                                $qtyFormatted = $item->qty == (int)$item->qty ? (int)$item->qty : $item->qty;
                                return "{$qtyFormatted} {$unitName} {$item->product->name}";
                            })->implode(', ');
                        @endphp
                        <div class="stok-card-item stok-card-package" title="Isi: {{ $itemsRingkas }}">
                            <div class="stok-card-qty-box">
                                <span class="stok-card-qty" style="color: var(--accent);">{{ number_format($ps->qty, 0, ',', '.') }}</span>
                                <span class="stok-card-qty-label">pack</span>
                            </div>
                            <div class="stok-card-info">
                                <span class="stok-badge-package">Paket</span>
                                <span class="stok-card-name" title="{{ $ps->package->name }}">{{ $ps->package->name }}</span>
                                <span class="stok-card-meta">Kode: {{ $ps->package->code }}</span>
                                <span class="stok-card-items-list" title="Isi: {{ $itemsRingkas }}">Isi: {{ $itemsRingkas }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
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
                        <div class="empty-state">
                            <div class="empty-icon-circle">
                                <i data-lucide="truck"></i>
                            </div>
                            <div class="empty-title">Belum ada laporan pengiriman</div>
                            <div class="empty-desc">Laporan pengiriman ke toko akan muncul di sini.</div>
                            <a href="{{ route('sales.delivery-reports.create') }}" class="empty-btn">
                                <i data-lucide="plus"></i> Buat Laporan Pertama
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
            <div class="empty-state">
                <div class="empty-icon-circle">
                    <i data-lucide="truck"></i>
                </div>
                <div class="empty-title">Belum ada laporan pengiriman</div>
                <div class="empty-desc">Laporan pengiriman ke toko akan muncul di sini.</div>
                <a href="{{ route('sales.delivery-reports.create') }}" class="empty-btn">
                    <i data-lucide="plus"></i> Buat Laporan Pertama
                </a>
            </div>
        @endforelse

        @if($reports->hasPages())
            <div style="margin-top:12px;">{{ $reports->links() }}</div>
        @endif
    </div>
    <script>
        function switchStokTab(tabId, event) {
            document.querySelectorAll('.stok-tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.stok-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            if (event) {
                event.currentTarget.classList.add('active');
            }
        }
    </script>
</x-layouts.user>

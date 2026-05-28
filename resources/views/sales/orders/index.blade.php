<x-layouts.user>
    <x-slot name="title">Riwayat Pengajuan</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }
        .page-desc   { font-size:13.5px;color:var(--muted);margin-top:4px; }

        .btn-primary {
            background:var(--brown);color:#fff;
            padding:9.5px 18px;border-radius:8px;
            text-decoration:none;font-size:13px;font-weight:700;
            display:inline-flex;align-items:center;gap:8px;
            transition:all 0.15s ease-in-out;white-space:nowrap;
            box-shadow:0 2px 4px rgba(42,23,14,0.1);
        }
        .btn-primary:hover { background:var(--brown-hover);box-shadow:0 4px 12px rgba(42,23,14,0.15); }

        /* ── Summary Cards ───────────────────── */
        .summary-row { display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px; }
        .summary-card {
            background:#fff;border:1px solid var(--border);border-radius:12px;
            padding:16px 18px;display:flex;flex-direction:column;gap:6px;
            box-shadow: 0 1px 2px rgba(42, 23, 14, 0.01);
        }
        .summary-value { font-size:24px;font-weight:800;color:var(--text);letter-spacing:-0.03em;line-height:1; }
        .summary-label { font-size:11.5px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.04em; }

        /* ── Table Card ──────────────────────── */
        .table-card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01); }
        .table-title { padding:12px 16px;border-bottom:1px solid var(--border);font-size:13.5px;font-weight:700;color:var(--text);background:var(--cream); }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:var(--cream);border-bottom:1px solid var(--border); }
        th { padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em; }
        td { padding:10px 16px;border-bottom:1px solid var(--border);font-size:13px;color:var(--text);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#faf8f5; }

        /* ── Status Badge ────────────────────── */
        .badge {
            display:inline-flex;align-items:center;justify-content:center;
            padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;
            line-height:1.2;text-transform:uppercase;letter-spacing:0.04em;
        }
        .badge-pending  { background:#fffbeb;color:#d97706;border:1px solid #fde68a; }
        .badge-approved { background:#ecfdf5;color:#047857;border:1px solid #a7f3d0; }
        .badge-done     { background:#eff6ff;color:#1d4ed8;border:1px solid #dbeafe; }
        .badge-canceled { background:#fef2f2;color:#b91c1c;border:1px solid #fecaca; }

        .btn-link {
            font-size:12.5px;font-weight:700;color:var(--brown);
            text-decoration:none;transition:color 0.15s, opacity 0.15s;
            display:inline-flex;align-items:center;gap:4px;cursor:pointer;
        }
        .btn-link:hover { color:var(--accent); text-decoration:underline; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { padding:56px 20px;text-align:center; background:#fff; border-radius:12px; border:1px solid var(--border); }
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
            padding: 12px 14px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .mobile-card-num {
            font-family: monospace;
            font-weight: 700;
            font-size: 12.5px;
            color: var(--text);
        }
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            margin-bottom: 4px;
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
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid rgba(234, 227, 210, 0.5);
            text-align: right;
            display: flex;
            justify-content: flex-end;
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 767px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
            .summary-row { grid-template-columns: 1fr; gap: 8px; }
            .summary-card { padding: 12px 14px; flex-direction: row; justify-content: space-between; align-items: center; }
            .summary-value { order: 2; font-size: 20px; }
            .summary-label { order: 1; margin: 0; }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengajuan</h1>
            <p class="page-desc">Status permintaan barang ke gudang.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn-primary">
            + Buat Pengajuan
        </a>
    </div>

    @php
        $baseQ = \App\Models\SalesOrder::where('sales_id', auth()->id());
        $counts = [
            'menunggu' => (clone $baseQ)->where('status','menunggu')->count(),
            'diproses' => (clone $baseQ)->where('status','diproses')->count(),
            'selesai'  => (clone $baseQ)->where('status','selesai')->count(),
        ];
    @endphp

    <div class="summary-row">
        <div class="summary-card">
            <div class="summary-value">{{ $counts['menunggu'] }}</div>
            <div class="summary-label">Menunggu Persetujuan</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ $counts['diproses'] }}</div>
            <div class="summary-label">Sudah Disetujui</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ $counts['selesai'] }}</div>
            <div class="summary-label">Selesai</div>
        </div>
    </div>

    <!-- Desktop View (Table Card) -->
    <div class="table-card desktop-only">
        <div class="table-title">Semua Pengajuan</div>
        <table>
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;color:var(--text);font-size:12px;">{{ $order->order_number }}</span>
                    </td>
                    <td style="font-weight:600;">{{ $order->customer->name ?? '— Stok Keliling' }}</td>
                    <td>
                        @php
                            $map = [
                                'menunggu'   => ['badge-pending',  'Menunggu'],
                                'diproses'   => ['badge-approved', 'Disetujui'],
                                'selesai'    => ['badge-done',     'Selesai'],
                                'dibatalkan' => ['badge-canceled', 'Batal'],
                            ];
                            [$cls,$lbl] = $map[$order->status] ?? ['badge-pending', $order->status];
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                    </td>
                    <td style="color:var(--muted); font-weight: 500;">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('sales.orders.show', $order) }}" class="btn-link">
                            Lihat <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-wrap" style="border:none;">
                            <div class="empty-emoji">📋</div>
                            <div class="empty-title">Belum ada pengajuan barang</div>
                            <a href="{{ route('sales.orders.create') }}" class="empty-cta">Buat Pengajuan Sekarang →</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
            <div style="padding:10px 16px;border-top:1px solid var(--border);">{{ $orders->links() }}</div>
        @endif
    </div>

    <!-- Mobile View (Card List) -->
    <div class="mobile-only">
        @forelse($orders as $order)
            @php
                [$cls,$lbl] = $map[$order->status] ?? ['badge-pending', $order->status];
            @endphp
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <span class="mobile-card-num">{{ $order->order_number }}</span>
                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Tujuan:</span>
                    <span class="mobile-card-val">{{ $order->customer->name ?? '— Stok Keliling' }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Tanggal:</span>
                    <span class="mobile-card-val">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="mobile-card-actions">
                    <a href="{{ route('sales.orders.show', $order) }}" class="btn-link">
                        Lihat Detail <i data-lucide="chevron-right" style="width:13px;height:13px;"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-wrap">
                <div class="empty-emoji">📋</div>
                <div class="empty-title">Belum ada pengajuan barang</div>
                <a href="{{ route('sales.orders.create') }}" class="empty-cta">Buat Pengajuan Sekarang →</a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div style="margin-top:12px;">{{ $orders->links() }}</div>
        @endif
    </div>

</x-layouts.user>

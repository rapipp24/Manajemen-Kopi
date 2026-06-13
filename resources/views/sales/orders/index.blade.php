<x-layouts.user>
    <x-slot name="title">Riwayat Pengajuan</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px; }
        .page-title  { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.02em; }
        .page-desc   { font-size:13.5px;color:var(--muted);margin-top:4px; }

        .btn-primary {
            background:var(--brown);color:#fff;
            padding:10px 18px;border-radius:10px;
            text-decoration:none;font-size:13px;font-weight:700;
            display:inline-flex;align-items:center;gap:8px;
            transition:all 0.2s ease;white-space:nowrap;
            box-shadow:0 4px 12px rgba(42,23,14,0.12);
        }
        .btn-primary:hover { background:var(--brown-hover);box-shadow:0 6px 16px rgba(42,23,14,0.18); transform: translateY(-1px); }

        /* ── Summary Cards ───────────────────── */
        .summary-row { display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px; }
        .summary-card {
            background:#fff;border:1px solid var(--border);border-radius:12px;
            padding:16px;display:flex;flex-direction:column;gap:6px;
            box-shadow: 0 4px 12px rgba(42, 23, 14, 0.02);
            transition: all 0.2s ease;
        }
        .summary-card:hover { border-color: var(--accent); transform: translateY(-1px); }
        .summary-value { font-size:22px;font-weight:800;color:var(--text);letter-spacing:-0.03em;line-height:1; }
        .summary-label { font-size:10.5px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em; }

        /* ── Table Card ──────────────────────── */
        .table-card { background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow: 0 4px 12px rgba(42, 23, 14, 0.02); }
        .table-title { padding:12px 16px;border-bottom:1px solid var(--border);font-size:13.5px;font-weight:700;color:var(--text);background:var(--cream); }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:var(--cream);border-bottom:1px solid var(--border); }
        th { padding:10px 14px;text-align:left;font-size:9.5px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em; }
        td { padding:12px 14px;border-bottom:1px solid var(--border);font-size:13px;color:var(--text);vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#faf8f5; }

        /* ── Status Badge ────────────────────── */
        .badge {
            display:inline-flex;align-items:center;justify-content:center;
            padding:2px 6px;border-radius:4px;font-size:9px;font-weight:700;
            line-height:1.2;text-transform:uppercase;letter-spacing:0.04em;
        }
        .badge-pending  { background:#fffbeb;color:#d97706;border:1px solid #fde68a; }
        .badge-approved { background:#ecfdf5;color:#047857;border:1px solid #a7f3d0; }
        .badge-done     { background:#eff6ff;color:#1d4ed8;border:1px solid #dbeafe; }
        .badge-canceled { background:#fef2f2;color:#b91c1c;border:1px solid #fecaca; }

        .btn-link {
            font-size:12px;font-weight:700;color:var(--brown);
            text-decoration:none;transition:color 0.15s, opacity 0.15s;
            display:inline-flex;align-items:center;gap:4px;cursor:pointer;
        }
        .btn-link:hover { color:var(--accent); text-decoration:underline; }

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

        /* ── Desktop/Mobile Dual Layout ──────── */
        .desktop-only { display: block; }
        .mobile-only { display: none; }

        .mobile-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 8px;
            box-shadow: 0 1px 3px rgba(42, 23, 14, 0.01);
            transition: border-color 0.15s;
        }
        .mobile-card:hover {
            border-color: var(--accent);
        }
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }
        .mobile-card-num {
            font-family: monospace;
            font-weight: 700;
            font-size: 12px;
            color: var(--text);
        }
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 3px;
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
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid rgba(234, 227, 210, 0.5);
            display: flex;
            justify-content: flex-end;
        }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 767px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
            .summary-row { gap: 8px; margin-bottom: 20px; }
            .summary-card { padding: 12px 10px; text-align: center; border-radius: 10px; }
            .summary-value { font-size: 18px; }
            .summary-label { font-size: 9.5px; line-height: 1.2; }
            .btn-primary { padding: 9px 16px; font-size: 12.5px; border-radius: 8px; }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengajuan</h1>
            <p class="page-desc">Status permintaan barang ke gudang.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn-primary">
            <i data-lucide="plus-circle" style="width:16px;height:16px;"></i> Buat Pengajuan
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
                        <a href="{{ route('sales.orders.show', $order) }}" class="sales-detail-link">
                            Lihat Detail <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon-circle">
                                <i data-lucide="clipboard-list"></i>
                            </div>
                            <div class="empty-title">Belum ada pengajuan</div>
                            <div class="empty-desc">Ajukan barang dari gudang agar stok sales siap digunakan.</div>
                            <a href="{{ route('sales.orders.create') }}" class="empty-btn">
                                <i data-lucide="plus"></i> Buat Pengajuan Sekarang
                            </a>
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
                    <a href="{{ route('sales.orders.show', $order) }}" class="sales-detail-link">
                        Lihat Detail <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon-circle">
                    <i data-lucide="clipboard-list"></i>
                </div>
                <div class="empty-title">Belum ada pengajuan</div>
                <div class="empty-desc">Ajukan barang dari gudang agar stok sales siap digunakan.</div>
                <a href="{{ route('sales.orders.create') }}" class="empty-btn">
                    <i data-lucide="plus"></i> Buat Pengajuan Sekarang
                </a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div style="margin-top:12px;">{{ $orders->links() }}</div>
        @endif
    </div>

</x-layouts.user>

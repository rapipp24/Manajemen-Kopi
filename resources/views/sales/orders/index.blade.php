<x-layouts.user>
    <x-slot name="title">Riwayat Pengajuan</x-slot>

    <style>
        /* ── Page Header ─────────────────────── */
        .page-header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px; }
        .page-title  { font-size:20px;font-weight:700;color:#1c1917;letter-spacing:-0.03em; }
        .page-desc   { font-size:13px;color:#78716c;margin-top:3px; }

        .btn-primary {
            background:#92400e;color:#fff;
            padding:9px 18px;border-radius:9px;
            text-decoration:none;font-size:13px;font-weight:600;
            display:inline-flex;align-items:center;gap:7px;
            transition:background 0.15s,box-shadow 0.15s;white-space:nowrap;
            box-shadow:0 1px 3px rgba(146,64,14,0.25);
        }
        .btn-primary:hover { background:#78350f;box-shadow:0 3px 8px rgba(146,64,14,0.3); }

        /* ── Summary Cards ───────────────────── */
        .summary-row { display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px; }
        .summary-card {
            background:#fff;border:1px solid #ece8e3;border-radius:12px;
            padding:16px 18px;display:flex;flex-direction:column;gap:4px;
        }
        .summary-value { font-size:26px;font-weight:800;color:#1c1917;letter-spacing:-0.03em;line-height:1; }
        .summary-label { font-size:11.5px;color:#78716c;font-weight:500; }

        /* ── Table Card ──────────────────────── */
        .table-card { background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden; }
        .table-title { padding:14px 18px;border-bottom:1px solid #f5f0eb;font-size:13px;font-weight:700;color:#1c1917; }

        table { width:100%;border-collapse:collapse; }
        thead tr { background:#fafaf9;border-bottom:1px solid #ece8e3; }
        th { padding:10px 16px;text-align:left;font-size:10px;font-weight:700;color:#b9a99a;text-transform:uppercase;letter-spacing:0.07em; }
        td { padding:13px 16px;border-bottom:1px solid #f5f0eb;font-size:13px;color:#44403c;vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fdfcfb; }

        /* ── Status Badge ────────────────────── */
        .badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;line-height:1.4; }
        .badge-pending  { background:#fef3c7;color:#92400e; }
        .badge-approved { background:#f0fdf4;color:#16a34a; }
        .badge-done     { background:#eff6ff;color:#1d4ed8; }
        .badge-canceled { background:#fef2f2;color:#dc2626; }

        .btn-link { font-size:12.5px;font-weight:600;color:#92400e;text-decoration:none; }
        .btn-link:hover { text-decoration:underline; }

        /* ── Empty ───────────────────────────── */
        .empty-wrap { padding:56px 20px;text-align:center; }
        .empty-emoji { font-size:34px;margin-bottom:10px;opacity:0.25; }
        .empty-title { font-size:14px;font-weight:600;color:#78716c;margin-bottom:8px; }
        .empty-cta { font-size:13px;color:#92400e;font-weight:600;text-decoration:none; }
        .empty-cta:hover { text-decoration:underline; }

        /* ── Responsive ──────────────────────── */
        @media (max-width: 600px) {
            .summary-row { grid-template-columns: repeat(3,1fr); gap:8px; }
            .summary-value { font-size:20px; }
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

    <div class="table-card">
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
                        <span style="font-family:monospace;font-weight:700;color:#1c1917;font-size:12px;">{{ $order->order_number }}</span>
                    </td>
                    <td>{{ $order->customer->name ?? '—' }}</td>
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
                    <td style="color:#78716c;">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('sales.orders.show', $order) }}" class="btn-link">Lihat →</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-wrap">
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
            <div style="padding:10px 16px;border-top:1px solid #f5f0eb;">{{ $orders->links() }}</div>
        @endif
    </div>

</x-layouts.user>

<x-layouts.user>
    <x-slot name="title">Riwayat Pengajuan</x-slot>

    <style>
        .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; }
        .page-title { font-size:20px; font-weight:700; color:#1c1917; letter-spacing:-0.02em; }
        .page-desc { font-size:13px; color:#78716c; margin-top:4px; }
        .btn-primary { background:#92400e; color:white; padding:9px 18px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:6px; transition:background 0.15s; }
        .btn-primary:hover { background:#78350f; }

        .summary-row { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:24px; }
        .summary-card { background:#fff; border:1px solid #e7e5e4; border-radius:10px; padding:16px 18px; }
        .summary-value { font-size:22px; font-weight:800; color:#1c1917; }
        .summary-label { font-size:12px; color:#78716c; margin-top:2px; }

        .table-card { background:#fff; border:1px solid #e7e5e4; border-radius:12px; overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:#fafaf9; border-bottom:1px solid #e7e5e4; }
        th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; color:#a8a29e; text-transform:uppercase; letter-spacing:0.05em; }
        td { padding:14px 16px; border-bottom:1px solid #f5f5f4; font-size:13.5px; color:#44403c; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:#fafaf9; }

        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
        .badge-pending  { background:#fef3c7; color:#92400e; }
        .badge-approved { background:#f0fdf4; color:#166534; }
        .badge-done     { background:#e0f2fe; color:#075985; }
        .badge-canceled { background:#fef2f2; color:#991b1b; }
        .btn-link { font-size:13px; font-weight:600; color:#92400e; text-decoration:none; }
        .btn-link:hover { text-decoration:underline; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Riwayat Pengajuan</h1>
            <p class="page-desc">Pantau status permintaan barang ke gudang.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i> Buat Pengajuan
        </a>
    </div>

    @php
        $baseQ = \App\Models\SalesOrder::where('sales_id', auth()->id());
        $counts = [
            'menunggu'   => (clone $baseQ)->where('status','menunggu')->count(),
            'diproses'   => (clone $baseQ)->where('status','diproses')->count(),
            'selesai'    => (clone $baseQ)->where('status','selesai')->count(),
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
            <div class="summary-label">Selesai Diambil</div>
        </div>
    </div>

    <div class="table-card">
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
                    <td style="font-family:monospace;font-weight:700;color:#1c1917;">{{ $order->order_number }}</td>
                    <td>{{ $order->customer->name ?? 'Stok Sales' }}</td>
                    <td>
                        @php
                            $map = ['menunggu'=>['badge-pending','Menunggu'],'diproses'=>['badge-approved','Disetujui'],'selesai'=>['badge-done','Selesai'],'dibatalkan'=>['badge-canceled','Batal']];
                            [$cls,$lbl] = $map[$order->status] ?? ['badge-pending',$order->status];
                        @endphp
                        <span class="badge {{ $cls }}">{{ $lbl }}</span>
                    </td>
                    <td style="color:#78716c;">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('sales.orders.show', $order) }}" class="btn-link">Lihat →</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:56px;text-align:center;color:#a8a29e;">
                        <i data-lucide="inbox" style="width:36px;height:36px;margin:0 auto 12px;display:block;opacity:0.3;"></i>
                        <div style="font-size:14px;font-weight:600;color:#78716c;margin-bottom:4px;">Belum ada pengajuan barang</div>
                        <a href="{{ route('sales.orders.create') }}" style="font-size:13px;color:#92400e;font-weight:600;text-decoration:none;">Buat Pengajuan Pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
            <div style="padding:12px 16px;border-top:1px solid #f5f5f4;">{{ $orders->links() }}</div>
        @endif
    </div>

</x-layouts.user>

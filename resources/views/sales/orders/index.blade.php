<x-layouts.user>
    <x-slot name="title">Riwayat Pengajuan</x-slot>

    <style>
        .page-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.02em; }
        .page-desc { color: #64748b; font-size: 14px; margin-bottom: 32px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px; }
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th {
            padding: 12px 24px;
            background: #f8fafc;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
        
        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-pending { background: #fff7ed; color: #9a3412; }
        .badge-approved { background: #f0fdf4; color: #166534; }
        .badge-completed { background: #f0f9ff; color: #075985; }
        .badge-canceled { background: #fef2f2; color: #991b1b; }

        .btn-new {
            background: #92400e;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn-new:hover { background: #78350f; }

        .btn-view {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .btn-view:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
    </style>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h1 class="page-title">Riwayat Pengajuan</h1>
            <p class="page-desc">Pantau status permintaan barang Anda ke gudang utama.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" class="btn-new">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
            Buat Pengajuan
        </a>
    </div>

    <div class="stats-grid">
        @php
            $baseQuery = \App\Models\SalesOrder::where('sales_id', auth()->id());
            $stats = [
                ['label' => 'Menunggu', 'count' => (clone $baseQuery)->where('status', 'menunggu')->count(), 'icon' => 'clock', 'bg' => '#fff7ed', 'color' => '#9a3412'],
                ['label' => 'Disetujui', 'count' => (clone $baseQuery)->where('status', 'diproses')->count(), 'icon' => 'check-circle', 'bg' => '#f0fdf4', 'color' => '#166534'],
                ['label' => 'Selesai', 'count' => (clone $baseQuery)->where('status', 'selesai')->count(), 'icon' => 'package-check', 'bg' => '#f0f9ff', 'color' => '#075985'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: {{ $stat['bg'] }}; color: {{ $stat['color'] }};">
                <i data-lucide="{{ $stat['icon'] }}" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">{{ $stat['label'] }}</div>
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stat['count'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Tujuan</th>
                    <th>Estimasi Nilai</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-family: monospace; font-weight: 700;">{{ $order->order_number }}</td>
                    <td>{{ $order->customer->name ?? 'Stok Keliling' }}</td>
                    <td style="font-weight: 700; color: #92400e;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $badgeMap = [
                                'menunggu' => 'badge-pending',
                                'diproses' => 'badge-approved',
                                'selesai' => 'badge-completed',
                                'dibatalkan' => 'badge-canceled',
                            ];
                            $labelMap = [
                                'menunggu' => 'Menunggu',
                                'diproses' => 'Disetujui',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Batal',
                            ];
                        @endphp
                        <span class="badge {{ $badgeMap[$order->status] ?? '' }}">
                            {{ $labelMap[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td style="color: #64748b;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('sales.orders.show', $order) }}" class="btn-view">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 64px; text-align: center; color: #94a3b8;">
                        <i data-lucide="clipboard-list" style="width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                        <p>Belum ada data pengajuan barang.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</x-layouts.user>x-layouts.user>

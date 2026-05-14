<x-layouts.admin>
    <x-slot name="title">Daftar Pengajuan Barang Sales</x-slot>

    <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Pengajuan Ambil Barang</h3>
                <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Kelola permintaan stok produk dari petugas sales lapangan.</p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">No. Pengajuan</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Petugas Sales</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tujuan / Toko</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Total Nilai</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #334155;">
                    @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px 24px; font-weight: 700; color: #0f172a;">{{ $order->order_number }}</td>
                        <td style="padding: 16px 24px;">{{ $order->sales->name }}</td>
                        <td style="padding: 16px 24px;">{{ $order->customer->name ?? 'Stok Pribadi / Keliling' }}</td>
                        <td style="padding: 16px 24px; font-weight: 600;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td style="padding: 16px 24px;">
                            @php
                                $statusColors = [
                                    'menunggu' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Persetujuan'],
                                    'diproses' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Disetujui'],
                                    'selesai' => ['bg' => '#e0f2fe', 'text' => '#075985', 'label' => 'Selesai'],
                                    'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak/Batal'],
                                ];
                                $color = $statusColors[$order->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $order->status];
                            @endphp
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                {{ $color['label'] }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; color: #64748b;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 16px 24px;">
                            <a href="{{ route('admin.sales-orders.show', $order) }}" style="color: #2563eb; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                Detail & Review
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #94a3b8;">Belum ada pengajuan barang dari sales.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>

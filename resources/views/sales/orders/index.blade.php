<x-layouts.user>
    <x-slot name="title">Pengajuan Barang Saya</x-slot>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: #1c1917;">Pengajuan Barang</h1>
            <p style="color: #78716c; font-size: 14px;">Daftar pengajuan ambil barang dari gudang/pabrik.</p>
        </div>
        <a href="{{ route('sales.orders.create') }}" 
           style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: opacity 0.2s;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Pengajuan Baru
        </a>
    </div>

    <div style="background: white; border-radius: 16px; border: 1px solid #e7e5e4; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #fafaf9;">
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">No. Pengajuan</th>
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Tujuan / Toko</th>
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Estimasi Nilai</th>
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; font-size: 11px; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #44403c;">
                    @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #f5f5f4;">
                        <td style="padding: 16px 24px; font-weight: 700; color: #1c1917;">{{ $order->order_number }}</td>
                        <td style="padding: 16px 24px;">{{ $order->customer->name ?? 'Stok Pribadi / Keliling' }}</td>
                        <td style="padding: 16px 24px; font-weight: 600;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td style="padding: 16px 24px;">
                            @php
                                $statusColors = [
                                    'menunggu' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Persetujuan'],
                                    'diproses' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Disetujui'],
                                    'selesai' => ['bg' => '#e0f2fe', 'text' => '#075985', 'label' => 'Selesai Diambil'],
                                    'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak/Batal'],
                                ];
                                $color = $statusColors[$order->status] ?? ['bg' => '#f5f5f4', 'text' => '#78716c', 'label' => $order->status];
                            @endphp
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                {{ $color['label'] }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; color: #78716c;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 16px 24px;">
                            <a href="{{ route('sales.orders.show', $order) }}" style="color: #92400e; text-decoration: none; font-weight: 600;">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: #a8a29e;">Anda belum pernah membuat pengajuan barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid #f5f5f4;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</x-layouts.user>

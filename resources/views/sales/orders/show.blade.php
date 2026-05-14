<x-layouts.user>
    <x-slot name="title">Status Pengajuan #{{ $order->order_number }}</x-slot>

    <div style="max-width: 900px; margin: 0 auto; margin-bottom: 60px;">
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('sales.orders.index') }}" style="color: #92400e; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar
            </a>
            
            @php
                $statusColors = [
                    'menunggu' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Persetujuan'],
                    'diproses' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Disetujui & Diproses'],
                    'selesai' => ['bg' => '#e0f2fe', 'text' => '#075985', 'label' => 'Barang Selesai Diambil'],
                    'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak / Dibatalkan'],
                ];
                $color = $statusColors[$order->status] ?? ['bg' => '#f5f5f4', 'text' => '#78716c', 'label' => $order->status];
            @endphp
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 14px; color: #78716c;">Status Pengajuan:</span>
                <span style="display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                    {{ $color['label'] }}
                </span>
            </div>
        </div>

        <div style="background: white; border-radius: 20px; border: 1px solid #e7e5e4; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="padding: 32px; border-bottom: 1px solid #f5f5f4; display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1 style="font-size: 20px; font-weight: 800; color: #1c1917; margin-bottom: 8px;">PENGAJUAN #{{ $order->order_number }}</h1>
                    <p style="color: #78716c; font-size: 14px;">Diajukan pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div style="text-align: right;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #a8a29e; text-transform: uppercase; margin-bottom: 4px;">Tujuan Barang</label>
                    <div style="font-size: 16px; font-weight: 700; color: #1c1917;">{{ $order->customer->name ?? 'Stok Pribadi / Keliling' }}</div>
                    <div style="font-size: 13px; color: #78716c;">{{ $order->customer ? ($order->customer->phone ?? '-') : 'Gudang Utama' }}</div>
                </div>
            </div>

            <div style="padding: 32px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f5f5f4;">
                            <th style="padding: 12px 0; text-align: left; font-size: 12px; color: #a8a29e; text-transform: uppercase;">Produk</th>
                            <th style="padding: 12px 0; text-align: right; font-size: 12px; color: #a8a29e; text-transform: uppercase;">Nilai Estimasi</th>
                            <th style="padding: 12px 0; text-align: center; font-size: 12px; color: #a8a29e; text-transform: uppercase;">Jumlah</th>
                            <th style="padding: 12px 0; text-align: right; font-size: 12px; color: #a8a29e; text-transform: uppercase;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr style="border-bottom: 1px solid #f5f5f4;">
                            <td style="padding: 20px 0;">
                                <div style="font-weight: 700; color: #1c1917;">{{ $item->product->name }}</div>
                            </td>
                            <td style="padding: 20px 0; text-align: right; color: #44403c;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td style="padding: 20px 0; text-align: center; font-weight: 600; color: #1c1917;">{{ $item->qty }}</td>
                            <td style="padding: 20px 0; text-align: right; font-weight: 800; color: #1c1917;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 32px; padding-top: 32px; border-top: 2px solid #f5f5f4; display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #a8a29e; text-transform: uppercase; margin-bottom: 8px;">Catatan Pengajuan</label>
                        <div style="font-size: 14px; color: #44403c; font-style: italic;">"{{ $order->catatan ?: 'Tidak ada catatan.' }}"</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; color: #78716c; margin-bottom: 4px;">Total Nilai Barang</div>
                        <div style="font-size: 28px; font-weight: 800; color: #92400e;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div style="padding: 24px 32px; background: #fafaf9; border-top: 1px solid #f5f5f4;">
                <h4 style="font-size: 12px; font-weight: 700; color: #a8a29e; text-transform: uppercase; margin-bottom: 12px;">Log Persetujuan</h4>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 10px; color: #a8a29e; margin-bottom: 4px;">Tgl Diajukan</label>
                        <div style="font-size: 12px; font-weight: 600;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 10px; color: #a8a29e; margin-bottom: 4px;">Disetujui Admin</label>
                        <div style="font-size: 12px; font-weight: 600;">{{ $order->processed_at ? $order->processed_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 10px; color: #a8a29e; margin-bottom: 4px;">Selesai Diambil</label>
                        <div style="font-size: 12px; font-weight: 600;">{{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 10px; color: #a8a29e; margin-bottom: 4px;">Status Stok Gudang</label>
                        <div style="font-size: 12px; font-weight: 600;">
                            @if($order->processed_at) <span style="color: #166534;">Sudah Terpotong</span>
                            @else <span style="color: #92400e;">Menunggu Admin</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.user>

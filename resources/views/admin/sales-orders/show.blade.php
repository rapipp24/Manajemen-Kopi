<x-layouts.admin>
    <x-slot name="title">Review Pengajuan Barang #{{ $salesOrder->order_number }}</x-slot>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; margin-bottom: 50px;">
        <!-- Kiri: Detail Items -->
        <div>
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); margin-bottom: 24px;">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Barang yang Diminta</h3>
                </div>
                <div style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                                <th style="padding: 12px 24px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Produk</th>
                                <th style="padding: 12px 24px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Stok Gudang</th>
                                <th style="padding: 12px 24px; text-align: center; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Qty Minta</th>
                                <th style="padding: 12px 24px; text-align: right; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Estimasi Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrder->items as $item)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 16px 24px;">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $item->product->name }}</div>
                                    <div style="font-size: 12px; color: #64748b;">SKU: {{ $item->product->sku }}</div>
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    <span style="font-weight: 600; color: {{ $item->product->current_stock < $item->qty ? '#ef4444' : '#166534' }};">
                                        {{ number_format($item->product->current_stock, 0, ',', '.') }} {{ $item->product->unit->name ?? '' }}
                                    </span>
                                </td>
                                <td style="padding: 16px 24px; text-align: center; font-weight: 700; font-size: 15px; color: #0f172a;">{{ number_format($item->qty, 0, ',', '.') }}</td>
                                <td style="padding: 16px 24px; text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f8fafc;">
                                <td colspan="3" style="padding: 16px 24px; text-align: right; font-weight: 700; color: #64748b;">TOTAL NILAI BARANG</td>
                                <td style="padding: 16px 24px; text-align: right; font-size: 18px; font-weight: 800; color: #0f172a;">Rp {{ number_format($salesOrder->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($salesOrder->catatan)
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px;">
                <h4 style="font-size: 13px; font-weight: 700; color: #92400e; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">Catatan Sales</h4>
                <p style="font-size: 14px; color: #92400e; margin: 0; line-height: 1.5;">{{ $salesOrder->catatan }}</p>
            </div>
            @endif
        </div>

        <!-- Kanan: Info & Action -->
        <div>
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); margin-bottom: 24px;">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Info Pengajuan</h3>
                </div>
                <div style="padding: 24px;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Status</label>
                        @php
                            $statusColors = [
                                'menunggu' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Menunggu Persetujuan'],
                                'diproses' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'Disetujui & Diproses'],
                                'selesai' => ['bg' => '#e0f2fe', 'text' => '#075985', 'label' => 'Selesai Diambil'],
                                'dibatalkan' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak / Batal'],
                            ];
                            $color = $statusColors[$salesOrder->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $salesOrder->status];
                        @endphp
                        <span style="display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                            {{ $color['label'] }}
                        </span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Nama Sales</label>
                        <div style="font-weight: 600; color: #0f172a;">{{ $salesOrder->sales->name }}</div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Tujuan / Toko</label>
                        <div style="font-weight: 600; color: #0f172a;">{{ $salesOrder->customer->name ?? 'Stok Pribadi / Keliling' }}</div>
                    </div>

                    <div style="padding-top: 16px; border-top: 1px solid #f1f5f9;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Log Waktu</label>
                        <div style="font-size: 12px; color: #475569; margin-bottom: 4px;">Dibuat: {{ $salesOrder->created_at->format('d M Y H:i') }}</div>
                        @if($salesOrder->processed_at) <div style="font-size: 12px; color: #166534; margin-bottom: 4px;">Disetujui: {{ $salesOrder->processed_at->format('d M Y H:i') }}</div> @endif
                    </div>
                </div>
            </div>

            @if($salesOrder->status === 'menunggu' || $salesOrder->status === 'diproses')
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Approval Admin</h3>
                </div>
                <div style="padding: 24px;">
                    <form action="{{ route('admin.sales-orders.update-status', $salesOrder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        @if($salesOrder->status === 'menunggu')
                        <button type="button" name="status" value="diproses" 
                                class="confirm-action"
                                data-confirm-title="Setujui Pengajuan?"
                                data-confirm-text="Stok gudang akan langsung dikurangi untuk pengajuan ini."
                                data-confirm-icon="question"
                                style="width: 100%; padding: 12px; background: #166534; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; margin-bottom: 12px;">
                            Setujui & Potong Stok
                        </button>
                        
                        <button type="button" name="status" value="dibatalkan" 
                                class="confirm-action"
                                data-confirm-title="Tolak Pengajuan?"
                                data-confirm-text="Pengajuan ini akan dibatalkan dan tidak akan memotong stok."
                                data-confirm-icon="warning"
                                style="width: 100%; padding: 10px; background: white; color: #ef4444; border: 1px solid #fee2e2; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            Tolak Pengajuan
                        </button>
                        @endif

                        @if($salesOrder->status === 'diproses')
                        <button type="button" name="status" value="selesai" 
                                class="confirm-action"
                                data-confirm-title="Selesaikan Pengajuan?"
                                data-confirm-text="Tandai bahwa barang sudah benar-benar diambil oleh sales."
                                data-confirm-icon="info"
                                style="width: 100%; padding: 12px; background: #075985; color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                            Barang Sudah Diambil
                        </button>
                        @endif
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>

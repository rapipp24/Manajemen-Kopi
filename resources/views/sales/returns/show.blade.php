<x-layouts.user>
    <x-slot name="title">Detail Return {{ $return->return_number }}</x-slot>

    <a href="{{ route('sales.returns.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#78716c;text-decoration:none;margin-bottom:18px;">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali ke Daftar Return
    </a>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;flex-wrap:wrap;">
        <span style="font-size:17px;font-weight:700;color:#1c1917;font-family:monospace;">{{ $return->return_number }}</span>
        @if($return->status === 'diterima')
            <span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check" style="width:12px;height:12px;"></i> DITERIMA</span>
        @elseif($return->status === 'ditolak')
            <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="x" style="width:12px;height:12px;"></i> DITOLAK</span>
        @else
            <span style="background:#fef08a;color:#854d0e;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="clock" style="width:12px;height:12px;"></i> MENUNGGU VERIFIKASI</span>
        @endif
    </div>

    <div style="display:grid;grid-template-columns:1fr 260px;gap:16px;align-items:start;">

        {{-- Kiri: Item yang direturn --}}
        <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden;">
            <div style="padding:13px 18px;border-bottom:1px solid #ece8e3;background:#fafaf8;">
                <h3 style="font-size:13px;font-weight:700;color:#1c1917;margin:0;">Produk yang Dikembalikan</h3>
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#fafaf8;border-bottom:1px solid #ece8e3;">
                        <th style="padding:9px 18px;text-align:left;font-size:10px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Produk</th>
                        <th style="padding:9px 18px;text-align:center;font-size:10px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Qty Return</th>
                        <th style="padding:9px 18px;text-align:right;font-size:10px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Harga/pcs</th>
                        <th style="padding:9px 18px;text-align:right;font-size:10px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Subtotal</th>
                        <th style="padding:9px 18px;text-align:left;font-size:10px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($return->items as $item)
                    <tr style="border-bottom:1px solid #f5f0eb;">
                        <td style="padding:12px 18px;">
                            <div style="font-weight:600;color:#1c1917;font-size:13px;">{{ $item->product->name }}</div>
                        </td>
                        <td style="padding:12px 18px;text-align:center;font-weight:700;color:#1c1917;">{{ number_format($item->qty_return, 0, ',', '.') }}</td>
                        <td style="padding:12px 18px;text-align:right;color:#78716c;font-size:13px;">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                        <td style="padding:12px 18px;text-align:right;font-weight:700;color:#1c1917;">Rp {{ number_format($item->subtotal_return, 0, ',', '.') }}</td>
                        <td style="padding:12px 18px;color:#78716c;font-size:12px;">{{ $item->reason ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#fafaf8;">
                        <td colspan="3" style="padding:12px 18px;text-align:right;font-size:10.5px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Total Nilai Return</td>
                        <td style="padding:12px 18px;text-align:right;font-size:16px;font-weight:800;color:#92400e;">
                            Rp {{ number_format($return->total_return, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Kanan: Info return --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            {{-- Info Return --}}
            <div style="background:#fff;border:1px solid #ece8e3;border-radius:12px;overflow:hidden;">
                <div style="padding:13px 16px;border-bottom:1px solid #ece8e3;background:#fafaf8;">
                    <h3 style="font-size:13px;font-weight:700;color:#1c1917;margin:0;">Info Return</h3>
                </div>
                <div style="padding:0;">
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid #f5f0eb;">
                        <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Laporan</span>
                        <span style="font-size:12px;font-weight:600;color:#1c1917;font-family:monospace;">{{ $return->deliveryReport->report_number }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid #f5f0eb;">
                        <span style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;">Tgl Return</span>
                        <span style="font-size:12px;font-weight:600;color:#1c1917;">{{ $return->return_date->format('d M Y') }}</span>
                    </div>
                    @if($return->note)
                    <div style="padding:10px 16px;border-bottom:1px solid #f5f0eb;">
                        <div style="font-size:11px;font-weight:700;color:#a8a29e;text-transform:uppercase;margin-bottom:4px;">Catatan</div>
                        <div style="font-size:12px;color:#57534e;font-style:italic;">{{ $return->note }}</div>
                    </div>
                    @endif
                    @if($return->status === 'ditolak' && $return->rejection_reason)
                    <div style="padding:10px 16px;background:#fff5f5;">
                        <div style="font-size:11px;font-weight:700;color:#991b1b;text-transform:uppercase;margin-bottom:4px;">Alasan Ditolak</div>
                        <div style="font-size:12px;color:#991b1b;">{{ $return->rejection_reason }}</div>
                    </div>
                    @endif
                    @if($return->approver)
                    <div style="padding:10px 16px;background:#f0fdf4;">
                        <div style="font-size:11px;font-weight:700;color:#166534;text-transform:uppercase;margin-bottom:4px;">
                            {{ $return->status === 'diterima' ? 'Diterima oleh' : 'Diproses oleh' }}
                        </div>
                        <div style="font-size:12px;font-weight:600;color:#166534;">{{ $return->approver->name }}</div>
                        <div style="font-size:11px;color:#86efac;">{{ $return->approved_at?->format('d M Y, H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('sales.delivery-reports.show', $return->deliveryReport) }}"
               style="display:inline-flex;align-items:center;justify-content:center;gap:6px;background:#fff;border:1px solid #d6d3d1;color:#57534e;text-decoration:none;padding:10px 16px;border-radius:8px;font-size:12.5px;font-weight:600;text-align:center;width:100%;box-sizing:border-box;">
                <i data-lucide="file-text" style="width:14px;height:14px;"></i> Lihat Laporan Pengiriman
            </a>
        </div>
    </div>
</x-layouts.user>

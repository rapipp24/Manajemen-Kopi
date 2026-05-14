<x-layouts.admin>
    <x-slot name="title">Detail Penjualan</x-slot>

    <div style="max-width: 900px; margin-bottom: 50px;">
        {{-- Flash Message --}}
        @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:14px 18px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Top Actions --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('admin.sales.index') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar Penjualan
            </a>
            
            <a href="{{ route('admin.sales.print', $sale) }}" target="_blank"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #0f172a; border-radius: 8px; font-size: 13px; font-weight: 600; color: white; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Nota
            </a>
        </div>

        {{-- HEADER INFO --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #0f172a; background: #e2e8f0; padding: 4px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        {{ $sale->invoice_number }}
                    </span>
                    <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                        Dicatat oleh {{ $sale->creator->name ?? '-' }} pada
                        {{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    @if($sale->payment_status === 'lunas')
                        <span style="font-size:13px;font-weight:700;background:#dcfce7;color:#166534;padding:6px 12px;border-radius:20px;border:1px solid #bbf7d0;">LUNAS</span>
                    @elseif($sale->payment_status === 'sebagian')
                        <span style="font-size:13px;font-weight:700;background:#fef9c3;color:#854d0e;padding:6px 12px;border-radius:20px;border:1px solid #fef08a;">DP/SEBAGIAN</span>
                    @else
                        <span style="font-size:13px;font-weight:700;background:#fee2e2;color:#991b1b;padding:6px 12px;border-radius:20px;border:1px solid #fecaca;">BELUM BAYAR</span>
                    @endif
                    <div style="font-size:12px;font-weight:600;color:#64748b;margin-top:8px;text-align:right;">
                        Metode: {{ strtoupper($sale->payment_method) }}
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Transaksi</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Customer</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ $sale->customer->name ?? 'Umum' }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Catatan</p>
                    <p style="font-size: 14px; color: #475569;">{{ $sale->note ?: '-' }}</p>
                </div>
            </div>
        </div>

        {{-- DETAIL ITEM --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Rincian Produk</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Produk</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Harga Satuan</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: center;">Qty</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotalQty = 0; @endphp
                    @foreach($sale->items as $item)
                    @php $grandTotalQty += $item->qty; @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 20px;">
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $item->product->name ?? '-' }}</div>
                            @if($item->product->variant)
                                <div style="font-size: 12px; color: #64748b;">{{ $item->product->variant }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; color: #475569; text-align: right;">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #1e293b; text-align: center;">
                            {{ number_format($item->qty) }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #1e293b; text-align: right;">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                        <td colspan="2" style="padding: 16px 20px; font-size: 14px; font-weight: 700; color: #475569; text-align: right;">Total</td>
                        <td style="padding: 16px 20px; font-size: 14px; font-weight: 800; color: #1e293b; text-align: center;">
                            {{ number_format($grandTotalQty) }}
                        </td>
                        <td style="padding: 16px 20px; font-size: 18px; font-weight: 800; color: #16a34a; text-align: right;">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-layouts.admin>

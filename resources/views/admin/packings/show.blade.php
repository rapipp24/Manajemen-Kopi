<x-layouts.admin>
    <x-slot name="title">Detail Packing</x-slot>

    <div style="max-width: 860px; margin-bottom: 50px;">

        {{-- Tombol kembali --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.packings.index') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.04);"
               onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.color='#1e293b';"
               onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.color='#475569';">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar Packing
            </a>
        </div>

        {{-- HEADER INFO --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #92400e; background: #fef3c7; padding: 4px 12px; border-radius: 6px; border: 1px solid #fde68a;">
                        {{ $packing->packing_number }}
                    </span>
                    <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                        Dicatat oleh {{ $packing->creator->name ?? '-' }} pada
                        {{ \Carbon\Carbon::parse($packing->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Packing</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ \Carbon\Carbon::parse($packing->packing_date)->format('d M Y') }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f0f9ff; border-radius: 8px; border: 1px solid #bae6fd;">
                    <p style="font-size: 11px; font-weight: 600; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Sumber Curah</p>
                    <p style="font-size: 15px; font-weight: 700; color: #0369a1;">
                        {{ $packing->curah_type ?: '-' }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Catatan</p>
                    <p style="font-size: 14px; color: #475569;">{{ $packing->note ?: '-' }}</p>
                </div>
            </div>
        </div>

        {{-- DETAIL ITEM --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Produk yang Dipacking</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Produk</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Jumlah Kemasan</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Berat/Kemasan</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Total Berat</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotalKemasan = 0; $grandTotalBerat = 0; @endphp
                    @foreach($packing->items as $item)
                    @php
                        $grandTotalKemasan += $item->qty_pack;
                        $grandTotalBerat   += $item->total_weight;
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 20px;">
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $item->product->name ?? '-' }}</div>
                            @if($item->product->variant)
                                <div style="font-size: 12px; color: #64748b;">{{ $item->product->variant }}</div>
                            @endif
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #1e293b; text-align: right;">
                            {{ number_format($item->qty_pack) }} pcs
                        </td>
                        <td style="padding: 12px 20px; font-size: 13px; color: #64748b; text-align: right;">
                            {{ number_format($item->weight_per_pack) }} gr
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #dc2626; text-align: right;">
                            {{ number_format($item->total_weight, 3) }} kg
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                        <td style="padding: 14px 20px; font-size: 13px; font-weight: 700; color: #475569;">Total</td>
                        <td style="padding: 14px 20px; font-size: 14px; font-weight: 800; color: #1e293b; text-align: right;">
                            {{ number_format($grandTotalKemasan) }} pcs
                        </td>
                        <td></td>
                        <td style="padding: 14px 20px; font-size: 14px; font-weight: 800; color: #dc2626; text-align: right;">
                            {{ number_format($grandTotalBerat, 3) }} kg
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- RINGKASAN --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">Ringkasan Packing</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="padding: 18px; background: #f0fdf4; border-radius: 10px; border: 1px solid #bbf7d0; text-align: center;">
                    <p style="font-size: 11px; font-weight: 600; color: #166534; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Kemasan</p>
                    <p style="font-size: 26px; font-weight: 800; color: #166534;">{{ number_format($grandTotalKemasan) }}</p>
                    <p style="font-size: 12px; color: #15803d;">pcs produk jadi</p>
                </div>
                <div style="padding: 18px; background: #fff5f5; border-radius: 10px; border: 1px solid #fecaca; text-align: center;">
                    <p style="font-size: 11px; font-weight: 600; color: #dc2626; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Curah Terpakai</p>
                    <p style="font-size: 26px; font-weight: 800; color: #dc2626;">{{ number_format($grandTotalBerat, 3) }}</p>
                    <p style="font-size: 12px; color: #ef4444;">kg dikurangi dari stok curah</p>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>

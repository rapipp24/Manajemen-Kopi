<x-layouts.admin>
    <x-slot name="title">Detail Produksi</x-slot>

    <div style="max-width: 860px; margin-bottom: 50px;">

        {{-- Tombol kembali --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.productions.index') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; text-decoration: none; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.04);"
               onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#cbd5e1'; this.style.color='#1e293b';"
               onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.color='#475569';">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Daftar Produksi
            </a>
        </div>

        {{-- HEADER INFO --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <span style="font-family: monospace; font-size: 18px; font-weight: 700; color: #92400e; background: #fef3c7; padding: 4px 12px; border-radius: 6px; border: 1px solid #fde68a;">
                        {{ $production->batch_number }}
                    </span>
                    <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                        Dicatat oleh {{ $production->creator->name ?? '-' }} pada
                        {{ \Carbon\Carbon::parse($production->created_at)->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Produksi</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">
                        {{ \Carbon\Carbon::parse($production->production_date)->format('d M Y') }}
                    </p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Jenis Produksi</p>
                    <p style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $production->product_type }}</p>
                </div>
                <div style="padding: 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Catatan</p>
                    <p style="font-size: 14px; color: #475569;">{{ $production->note ?: '-' }}</p>
                </div>
            </div>
        </div>

        {{-- BAHAN YANG DIGUNAKAN --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Bahan Baku Digunakan</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Bahan Baku</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: left;">Satuan</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-align: right;">Qty Digunakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($production->items as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 600; color: #1e293b;">
                            {{ $item->rawMaterial->name ?? '-' }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 13px; color: #64748b;">
                            {{ $item->rawMaterial->unit->name ?? '-' }}
                        </td>
                        <td style="padding: 12px 20px; font-size: 14px; font-weight: 700; color: #dc2626; text-align: right;">
                            {{ number_format($item->qty_used, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- RINGKASAN HASIL --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <h3 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">Ringkasan Hasil Produksi</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">

                <div style="padding: 18px; background: #fffbeb; border-radius: 10px; border: 1px solid #fde68a; text-align: center;">
                    <p style="font-size: 11px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Bahan</p>
                    <p style="font-size: 22px; font-weight: 800; color: #92400e;">
                        {{ number_format($production->total_material_used, 2) }}
                    </p>
                    <p style="font-size: 12px; color: #b45309;">kg</p>
                </div>

                <div style="padding: 18px; background: #f0fdf4; border-radius: 10px; border: 1px solid #bbf7d0; text-align: center;">
                    <p style="font-size: 11px; font-weight: 600; color: #166534; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Hasil</p>
                    <p style="font-size: 22px; font-weight: 800; color: #166534;">
                        {{ number_format($production->total_output, 2) }}
                    </p>
                    <p style="font-size: 12px; color: #15803d;">kg</p>
                </div>

                <div style="padding: 18px; background: #fff5f5; border-radius: 10px; border: 1px solid #fecaca; text-align: center;">
                    <p style="font-size: 11px; font-weight: 600; color: #dc2626; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Susut</p>
                    <p style="font-size: 22px; font-weight: 800; color: #dc2626;">
                        {{ number_format($production->shrinkage, 2) }}
                    </p>
                    <p style="font-size: 12px; color: #ef4444;">kg</p>
                </div>

            </div>
        </div>

    </div>
</x-layouts.admin>

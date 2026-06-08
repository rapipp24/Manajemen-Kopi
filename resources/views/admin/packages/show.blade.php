<x-layouts.admin>
    <x-slot name="title">Detail Paket: {{ $package->name }}</x-slot>

    <div style="margin-bottom: 24px; display: flex; gap: 12px; align-items: center;">
        <a href="{{ route('admin.packages.index') }}" 
           style="background: white; border: 1px solid var(--border); color: var(--text-mid); text-decoration: none; padding: 8px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; transition: all 0.2s;"
           onmouseover="this.style.background='var(--cream-100)'" onmouseout="this.style.background='white'">
            &larr; Kembali ke Daftar
        </a>
        <a href="{{ route('admin.packages.edit', $package->id) }}" 
           style="background: #0284c7; color: white; text-decoration: none; padding: 8px 16px; border-radius: 10px; font-size: 13.5px; font-weight: 600; transition: all 0.2s;"
           onmouseover="this.style.background='#0369a1'" onmouseout="this.style.background='#0284c7'">
            Edit Paket
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start; margin-bottom: 40px;">
        <!-- Left: Package Info Card -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 20px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--text-main); border-bottom: 1px solid var(--border); padding-bottom: 10px; margin: 0 0 16px 0;">
                Informasi Ringkas
            </h3>
            
            <div style="margin-bottom: 16px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Kode Paket</span>
                <code style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $package->code }}</code>
            </div>

            <div style="margin-bottom: 16px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Nama Paket</span>
                <span style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $package->name }}</span>
            </div>

            <div style="margin-bottom: 16px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Harga Paket</span>
                <span style="font-size: 18px; font-weight: 800; color: var(--brown-500);">Rp {{ number_format($package->selling_price, 0, ',', '.') }}</span>
            </div>

            <div style="margin-bottom: 16px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Status Keaktifan</span>
                @if($package->is_active)
                    <span style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Aktif</span>
                @else
                    <span style="background: #fff5f5; color: #be123c; border: 1px solid #fecaca; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Non-Aktif</span>
                @endif
            </div>

            <div>
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Deskripsi / Catatan</span>
                <p style="font-size: 13px; color: var(--text-mid); line-height: 1.5; margin: 0; white-space: pre-line;">{{ $package->description ?? 'Tidak ada catatan.' }}</p>
            </div>
        </div>

        <!-- Right: Components & Price Comparison -->
        <div>
            <!-- Table of Components -->
            <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02); margin-bottom: 24px;">
                <div style="padding: 16px 20px; border-bottom: 1.5px solid var(--border); background: #fffdfb;">
                    <h3 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                        Daftar Produk Penyusun (Komponen)
                    </h3>
                </div>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                            <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Produk</th>
                            <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Qty</th>
                            <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Harga Normal (Pcs)</th>
                            <th style="padding: 12px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Subtotal Normal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalNormalPrice = 0.0; @endphp
                        @foreach($package->items as $item)
                            @php 
                                $subtotalNormal = $item->qty * $item->product->price; 
                                $totalNormalPrice += $subtotalNormal;
                            @endphp
                            <tr style="border-bottom: 1px solid #fcf6ee;">
                                <td style="padding: 14px 20px;">
                                    <div style="font-size: 13.5px; font-weight: 700; color: var(--text-main);">{{ $item->product->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Variant: {{ $item->product->variant ?? '-' }} | Kode: {{ $item->product->code }}</div>
                                </td>
                                <td style="padding: 14px 20px; font-size: 13.5px; color: var(--text-mid); text-align: center; font-weight: 600;">
                                    {{ number_format($item->qty, 0, ',', '.') }} {{ $item->product->unit->name ?? 'Pcs' }}
                                </td>
                                <td style="padding: 14px 20px; font-size: 13.5px; color: var(--text-mid); text-align: right; font-weight: 600;">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </td>
                                <td style="padding: 14px 20px; font-size: 13.5px; color: var(--text-main); font-weight: 700; text-align: right;">
                                    Rp {{ number_format($subtotalNormal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Price Comparison Summary Box -->
            <div style="background: #faf8f6; border: 1.5px dashed var(--border); border-radius: 16px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin: 0 0 12px 0;">
                    Analisis Harga & Bundel
                </h4>
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13.5px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-mid);">Total Harga Komponen Normal:</span>
                        <span style="font-weight: 600; color: var(--text-main);">Rp {{ number_format($totalNormalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-mid);">Harga Jual Paket Khusus:</span>
                        <span style="font-weight: 700; color: var(--brown-500);">Rp {{ number_format($package->selling_price, 0, ',', '.') }}</span>
                    </div>
                    
                    @php $saving = $totalNormalPrice - $package->selling_price; @endphp
                    <div style="border-top: 1px solid var(--border); padding-top: 10px; display: flex; justify-content: space-between; font-weight: 700;">
                        @if($saving > 0)
                            <span style="color: #166534;">Hemat/Potongan Khusus Paket:</span>
                            <span style="color: #166534;">Rp {{ number_format($saving, 0, ',', '.') }} ({{ round(($saving / $totalNormalPrice) * 100) }}%)</span>
                        @elseif($saving == 0)
                            <span style="color: var(--text-muted);">Selisih Harga:</span>
                            <span style="color: var(--text-muted);">Rp 0 (Sama dengan harga normal)</span>
                        @else
                            <span style="color: #be123c;">Tambahan/Markup Paket:</span>
                            <span style="color: #be123c;">Rp {{ number_format(abs($saving), 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

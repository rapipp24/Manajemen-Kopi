<x-layouts.admin>
    <x-slot name="title">Stok Bahan Baku</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: var(--text-muted); font-size: 14px;">Kelola daftar dan stok bahan baku kopi Anda.</p>
        <a href="{{ route('admin.raw-materials.create') }}" 
           style="background: var(--brown-500); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
           onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
            + Tambah Bahan Baku
        </a>
    </div>

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Daftar Bahan Baku
            </h3>
            <form action="{{ route('admin.raw-materials.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..." 
                       style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; min-width: 200px; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                <button type="submit" style="background: var(--brown-500); color: white; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Bahan Baku</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Stok Saat Ini</th>
                    <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $material->name }}</div>
                        <div style="font-size: 12px; font-family: monospace; color: var(--text-muted); margin-top: 2px;">{{ $material->code }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 15px; font-weight: 800; color: var(--text-main);">
                            @php
                                $stock = (float) $material->current_stock;
                                $unit = $material->unit->code ?? 'kg';
                                
                                // Eksperimen Konversi Ton
                                if (strtolower($unit) == 'kg' && $stock >= 1000) {
                                    $tonVal = $stock / 1000;
                                    $formattedStock = floor($tonVal) == $tonVal 
                                        ? number_format($tonVal, 0, ',', '.') 
                                        : rtrim(rtrim(number_format($tonVal, 2, ',', '.'), '0'), ',');
                                    $displayStock = $formattedStock . ' Ton';
                                } else {
                                    $formattedStock = floor($stock) == $stock 
                                        ? number_format($stock, 0, ',', '.') 
                                        : rtrim(rtrim(number_format($stock, 2, ',', '.'), '0'), ',');
                                    $displayStock = $formattedStock . ' ' . $unit;
                                }
                            @endphp
                            {{ $displayStock }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                            Batas Aman: {{ floor((float)$material->minimum_stock) == (float)$material->minimum_stock ? number_format($material->minimum_stock, 0, ',', '.') : rtrim(rtrim(number_format($material->minimum_stock, 2, ',', '.'), '0'), ',') }} {{ $unit }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($material->current_stock <= 0)
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff5f5; color: #be123c; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fecaca;">
                                <span style="width: 5px; height: 5px; background: #be123c; border-radius: 50%;"></span>
                                HABIS
                            </span>
                        @elseif($material->current_stock <= $material->minimum_stock)
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #fffbeb; color: var(--brown-500); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fde68a;">
                                <span style="width: 5px; height: 5px; background: var(--brown-500); border-radius: 50%;"></span>
                                MENIPIS
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #bbf7d0;">
                                <span style="width: 5px; height: 5px; background: #166534; border-radius: 50%;"></span>
                                AMAN
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.raw-materials.movements', $material->id) }}" 
                               style="background: var(--cream-100); border: 1px solid var(--border); color: var(--text-mid); padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s;"
                               onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                                Riwayat
                            </a>
                            <a href="{{ route('admin.raw-materials.edit', $material->id) }}" 
                               style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Edit
                            </a>
                            <form action="{{ route('admin.raw-materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus bahan baku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;"
                                        onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                        <div style="margin-bottom: 8px;">Belum ada data bahan baku yang terdaftar.</div>
                        <a href="{{ route('admin.raw-materials.create') }}" style="color: var(--brown-500); font-weight: 700; text-decoration: underline;">Tambah Pertama Kali</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materials->hasPages())
    <div style="margin-top: 20px;">
        {{ $materials->links() }}
    </div>
    @endif
</x-layouts.admin>

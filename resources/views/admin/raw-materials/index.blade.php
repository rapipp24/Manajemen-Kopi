<x-layouts.admin>
    <x-slot name="title">Stok Bahan Baku</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #64748b; font-size: 14px;">Kelola daftar dan stok bahan baku kopi Anda.</p>
        <a href="{{ route('admin.raw-materials.create') }}" 
           style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            + Tambah Bahan Baku
        </a>
    </div>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: #92400e; border-radius: 2px;"></span>
                Daftar Bahan Baku
            </h3>
            <form action="{{ route('admin.raw-materials.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..." 
                       style="padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; min-width: 200px; outline: none; transition: border-color 0.2s;">
                <button type="submit" style="background: #92400e; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Bahan Baku</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Stok Saat Ini</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $material->name }}</div>
                        <div style="font-size: 12px; font-family: monospace; color: #94a3b8; margin-top: 2px;">{{ $material->code }}</div>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 15px; font-weight: 800; color: #1e293b;">
                            @php
                                $stock = $material->current_stock;
                                $unit = $material->unit->code ?? 'kg';
                                $displayStock = number_format($stock, 2, ',', '.');
                                
                                // Eksperimen Konversi Ton
                                if (strtolower($unit) == 'kg' && $stock >= 1000) {
                                    $displayStock = number_format($stock / 1000, 2, ',', '.') . ' Ton';
                                } else {
                                    $displayStock = $displayStock . ' ' . $unit;
                                }
                            @endphp
                            {{ $displayStock }}
                        </div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                            Batas Aman: {{ number_format($material->minimum_stock, 2, ',', '.') }} {{ $unit }}
                        </div>
                    </td>
                    <td style="padding: 16px 20px;">
                        @if($material->current_stock <= 0)
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #1e293b; color: #f8fafc; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid #0f172a;">
                                <span style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%;"></span>
                                HABIS
                            </span>
                        @elseif($material->current_stock <= $material->minimum_stock)
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff7ed; color: #c2410c; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid #fed7aa;">
                                <span style="width: 6px; height: 6px; background: #f97316; border-radius: 50%;"></span>
                                MENIPIS
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid #dcfce7;">
                                <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%;"></span>
                                AMAN
                            </span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <a href="{{ route('admin.raw-materials.movements', $material->id) }}" 
                               style="background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                                Riwayat
                            </a>
                            <a href="{{ route('admin.raw-materials.edit', $material->id) }}" 
                               style="background: #f0f9ff; border: 1px solid #e0f2fe; color: #0284c7; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;">
                                Edit
                            </a>
                            <form action="{{ route('admin.raw-materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus bahan baku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: #fff1f2; border: 1px solid #ffe4e6; color: #be123c; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 60px; text-align: center; color: #94a3b8; font-size: 14px;">
                        <div style="margin-bottom: 8px;">No raw materials found.</div>
                        <a href="{{ route('admin.raw-materials.create') }}" style="color: #92400e; text-decoration: underline;">Add your first material</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $materials->links() }}
    </div>
</x-layouts.admin>

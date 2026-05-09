<x-layouts.admin>
    <x-slot name="title">Stok Bahan Baku</x-slot>

    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #64748b; font-size: 14px;">Kelola daftar dan stok bahan baku kopi Anda.</p>
        <a href="{{ route('admin.raw-materials.create') }}" 
           style="background: #92400e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            + Tambah Bahan Baku
        </a>
    </div>

    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 600; color: #1e293b;">Daftar Bahan Baku</h3>
            <form action="{{ route('admin.raw-materials.index') }}" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." 
                       style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                <button type="submit" style="background: #f1f5f9; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; font-size: 13px; cursor: pointer;">Cari</button>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Kode</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Nama Bahan</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Stok Saat Ini</th>
                    <th style="padding: 14px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 14px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px 20px; font-size: 13px; font-family: monospace; color: #64748b;">{{ $material->code }}</td>
                    <td style="padding: 16px 20px; font-size: 14px; font-weight: 600; color: #1e293b;">{{ $material->name }}</td>
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 2px;">
                            {{ number_format($material->current_stock, 2, ',', '.') }} {{ $material->unit->code ?? '' }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted);">Min: {{ number_format($material->minimum_stock, 2, ',', '.') }}</div>
                    </td>
                    <td style="padding: 16px 20px; font-size: 14px;">
                        @if($material->current_stock == 0)
                            <span style="background: #1e293b; color: #f8fafc; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #0f172a;">HABIS</span>
                        @elseif($material->current_stock <= $material->minimum_stock)
                            <span style="background: #fff7ed; color: #c2410c; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fed7aa;">MENIPIS</span>
                        @else
                            <span style="background: #f0fdf4; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #dcfce7;">AMAN</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; text-align: right;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                            <a href="{{ route('admin.raw-materials.movements', $material->id) }}" style="color: #059669; text-decoration: none; font-size: 13px; font-weight: 500;">Riwayat</a>
                            <a href="{{ route('admin.raw-materials.edit', $material->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 500;">Edit</a>
                            <form action="{{ route('admin.raw-materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus bahan baku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 500;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">Belum ada data bahan baku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $materials->links() }}
    </div>
</x-layouts.admin>

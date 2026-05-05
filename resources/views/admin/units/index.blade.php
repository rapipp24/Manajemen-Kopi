<x-layouts.admin>
    <x-slot name="title">Data Satuan</x-slot>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px; align-items: start;">
        
        <!-- First Column: Table & Pagination -->
        <div>
            <!-- Table List -->
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 15px; font-weight: 600; color: #1e293b;">Daftar Satuan</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Kode</th>
                        <th style="padding: 12px 20px; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Nama Satuan</th>
                        <th style="padding: 12px 20px; text-align: right; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px; font-size: 14px; font-weight: 600; color: #0f172a;">{{ $unit->code }}</td>
                        <td style="padding: 14px 20px; font-size: 14px; color: #475569;">{{ $unit->name }}</td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('admin.units.edit', $unit->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13px; font-weight: 500;">Edit</a>
                                <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus satuan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px; font-weight: 500;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #94a3b8; font-size: 14px;">Belum ada data satuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 15px;">
            {{ $units->links() }}
        </div>
    </div>

        <!-- Add Form -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px;">
            <h3 style="font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 20px;">Tambah Satuan Baru</h3>
            <form action="{{ route('admin.units.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px;">Kode Satuan (Misal: kg, gr)</label>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="10" placeholder="Contoh: kg"
                           style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('code') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('code') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px;">Nama Lengkap (Misal: Kilogram)</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kilogram"
                           style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('name') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <button type="submit" 
                        style="width: 100%; padding: 12px; background: #92400e; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Simpan Satuan
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>

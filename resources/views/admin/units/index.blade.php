<x-layouts.admin>
    <x-slot name="title">Data Satuan</x-slot>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start;">

        <!-- First Column: Table & Pagination -->
        <div>
            <!-- Table List -->
            <div style="background: white; border-radius: 16px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
                <div style="padding: 20px 24px; border-bottom: 1.5px solid var(--border); background: #fffdfb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                        <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                        Daftar Satuan
                    </h3>
                    <form action="{{ route('admin.units.index') }}" method="GET" style="display: flex; gap: 8px;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari satuan..."
                            style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; outline: none; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                        <button type="submit" style="background: var(--brown-500); color: white; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s;"
                            onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">Cari</button>
                    </form>
                </div>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #fffdfb; border-bottom: 1.5px solid var(--border);">
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Kode</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nama Satuan</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Kategori</th>
                            <th style="padding: 14px 20px; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 14px 20px; text-align: right; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                        <tr style="border-bottom: 1px solid #fcf6ee; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fffdfb'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 14px 20px; font-size: 14px; font-weight: 700; color: var(--text-main);">{{ $unit->code }}</td>
                            <td style="padding: 14px 20px; font-size: 13.5px; color: var(--text-mid);">{{ $unit->name }}</td>
                            <td style="padding: 14px 20px;">
                                @if($unit->type == 'bahan_baku')
                                    <span style="font-size: 11px; font-weight: 700; color: var(--brown-500); background: #fffbeb; padding: 4px 10px; border-radius: 20px; border: 1px solid #fde68a;">Bahan Baku</span>
                                @elseif($unit->type == 'produk')
                                    <span style="font-size: 11px; font-weight: 700; color: #0369a1; background: #f0f9ff; padding: 4px 10px; border-radius: 20px; border: 1px solid #b3e0ff;">Produk Jadi</span>
                                @else
                                    <span style="font-size: 11px; font-weight: 700; color: #475569; background: #f8fafc; padding: 4px 10px; border-radius: 20px; border: 1px solid #cbd5e1;">Keduanya</span>
                                @endif
                            </td>
                            <td style="padding: 14px 20px;">
                                @if($unit->is_active)
                                <span style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Aktif</span>
                                @else
                                <span style="background: #fff5f5; color: #be123c; border: 1px solid #fecaca; display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                    <a href="{{ route('admin.units.edit', $unit->id) }}" style="color: #0284c7; text-decoration: none; font-size: 13.5px; font-weight: 600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Edit</a>
                                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus satuan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13.5px; font-weight: 600; padding: 0;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted); font-size: 14px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 44px; height: 44px; margin: 0 auto 12px; opacity: 0.35; color: var(--text-muted);">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <div>Belum ada data satuan yang terdaftar.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($units->hasPages())
            <div style="margin-top: 20px;">
                {{ $units->links() }}
            </div>
            @endif
        </div>

        <!-- Add Form -->
        <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                Tambah Satuan Baru
            </h3>
            <form action="{{ route('admin.units.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Kode Satuan (Misal: kg, gr)</label>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="10" placeholder="Contoh: kg"
                        style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('code') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('code') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Nama Lengkap (Misal: Kilogram)</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kilogram"
                        style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('name') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 8px;">Gunakan Untuk</label>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="bahan_baku" {{ old('type') == 'bahan_baku' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Khusus Bahan Baku</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="produk" {{ old('type') == 'produk' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Khusus Produk Jadi</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="semua" {{ old('type', 'semua') == 'semua' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Gunakan Untuk Keduanya</span>
                        </label>
                    </div>
                    @error('type') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                    style="width: 100%; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                    onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan Satuan
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>
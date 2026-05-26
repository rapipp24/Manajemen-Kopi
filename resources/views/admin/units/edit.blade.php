<x-layouts.admin>
    <x-slot name="title">Edit Satuan</x-slot>

    <div style="max-width: 500px; margin-bottom: 50px;">
        <form action="{{ route('admin.units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                    Data Satuan
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Nama Satuan</label>
                    <input type="text" name="name" value="{{ old('name', $unit->name) }}" required 
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('name') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Kode Singkat</label>
                    <input type="text" name="code" value="{{ old('code', $unit->code) }}" required 
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('code') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; text-transform: lowercase; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('code') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 8px;">Gunakan Untuk</label>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="bahan_baku" {{ old('type', $unit->type) == 'bahan_baku' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Khusus Bahan Baku</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="produk" {{ old('type', $unit->type) == 'produk' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Khusus Produk Jadi</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: #fffdfb; transition: all 0.2s;">
                            <input type="radio" name="type" value="semua" {{ old('type', $unit->type) == 'semua' ? 'checked' : '' }} style="accent-color: var(--brown-500);">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-mid);">Gunakan Untuk Keduanya</span>
                        </label>
                    </div>
                    @error('type') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="padding-top: 15px; border-top: 1.5px solid var(--border);">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $unit->is_active) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: var(--brown-500); cursor: pointer;">
                        <div>
                            <span style="font-size: 13.5px; font-weight: 700; color: var(--text-main); display: block;">Satuan Aktif</span>
                            <span style="font-size: 11.5px; color: var(--text-muted);">Hilangkan centang jika satuan ini tidak digunakan lagi.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.units.index') }}" 
                   style="flex: 1; padding: 12px; background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s;"
                   onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

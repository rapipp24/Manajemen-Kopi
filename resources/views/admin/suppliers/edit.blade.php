<x-layouts.admin>
    <x-slot name="title">Edit Supplier</x-slot>

    <div style="max-width: 700px; margin-bottom: 50px;">
        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 24px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 4px; height: 16px; background: var(--brown-500); border-radius: 2px;"></span>
                    Identitas Supplier
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Nama Perusahaan / Petani</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required 
                           style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                           onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    @error('name') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" 
                               style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('contact_person') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                               onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                        @error('contact_person') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">No. Telepon / WA</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required
                               style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                               onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                        @error('phone') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Alamat Kantor/Kebun</label>
                    <textarea name="address" rows="3" required
                              style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('address') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: border-color 0.15s;"
                              onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">{{ old('address', $supplier->address) }}</textarea>
                    @error('address') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12.5px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px;">Keterangan Tambahan</label>
                    <textarea name="description" rows="2"
                              style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('description') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: border-color 0.15s;"
                              onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">{{ old('description', $supplier->description) }}</textarea>
                    @error('description') <span style="color: #ef4444; font-size: 11px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="padding-top: 15px; border-top: 1.5px solid var(--border);">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: var(--brown-500); cursor: pointer;">
                        <div>
                            <span style="font-size: 13.5px; font-weight: 700; color: var(--text-main); display: block;">Supplier Aktif</span>
                            <span style="font-size: 11.5px; color: var(--text-muted);">Hilangkan centang jika sudah tidak bekerjasama dengan supplier ini.</span>
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
                <a href="{{ route('admin.suppliers.index') }}" 
                   style="flex: 1; padding: 12px; background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s;"
                   onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

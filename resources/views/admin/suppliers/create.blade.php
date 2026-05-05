<x-layouts.admin>
    <x-slot name="title">Tambah Supplier</x-slot>

    <div style="max-width: 600px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px;">
        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Nama Perusahaan / Supplier</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT. Biji Kopi Makmur"
                       style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Nama orang yg bisa dihubungi"
                           style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('contact_person') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('contact_person') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 8px;">No. Telepon / WA</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456xxx"
                           style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('phone') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-bottom: 28px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Alamat Lengkap</label>
                <textarea name="address" rows="3" placeholder="Alamat kantor atau gudang supplier"
                          style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('address') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; font-family: inherit;">{{ old('address') }}</textarea>
                @error('address') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" 
                        style="flex: 1; padding: 12px; background: #92400e; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Simpan Supplier
                </button>
                <a href="{{ route('admin.suppliers.index') }}" 
                   style="flex: 1; padding: 12px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

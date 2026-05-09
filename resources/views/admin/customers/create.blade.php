<x-layouts.admin>
    <x-slot name="title">Tambah Pelanggan Baru</x-slot>

    <div style="max-width: 600px; margin-bottom: 50px;">
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Profil Pelanggan
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="Nama pelanggan"
                           style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">No. Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           placeholder="08123456xxx"
                           style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('phone') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 0;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Alamat <span style="font-weight: 400; color: #94a3b8;">(Opsional)</span></label>
                    <textarea name="address" rows="3"
                              placeholder="Alamat pelanggan"
                              style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('address') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('address') }}</textarea>
                    @error('address') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 14px; background: #92400e; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer;">
                    Simpan Pelanggan
                </button>
                <a href="{{ route('admin.customers.index') }}" 
                   style="flex: 1; padding: 14px; background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Tambah Supplier Baru</x-slot>

    <div style="max-width: 700px; margin-bottom: 50px;">
        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf

            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Identitas Supplier
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Nama Perusahaan / Petani</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: PT. Biji Kopi Makmur"
                        style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                            placeholder="Nama PIC"
                            style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('contact_person') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                        @error('contact_person') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">No. Telepon / WA</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                            placeholder="08123456xxx"
                            style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                        @error('phone') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Alamat Kantor/Kebun</label>
                    <textarea name="address" rows="3" required
                        placeholder="Alamat lengkap supplier"
                        style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('address') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('address') }}</textarea>
                    @error('address') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 0;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Keterangan Tambahan</label>
                    <textarea name="description" rows="2"
                        placeholder="Misal: Supplier khusus Arabika Gayo"
                        style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('description') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('description') }}</textarea>
                    @error('description') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit"
                    style="flex: 2; padding: 14px; background: #92400e; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer;">
                    Simpan Supplier
                </button>
                <a href="{{ route('admin.suppliers.index') }}"
                    style="flex: 1; padding: 14px; background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
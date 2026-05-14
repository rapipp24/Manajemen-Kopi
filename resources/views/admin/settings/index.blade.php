<x-layouts.admin>
    <x-slot name="title">Pengaturan Nota & Toko</x-slot>

    <div style="max-width: 800px; margin-bottom: 50px;">
        <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="padding: 24px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px;">
                    <svg style="width: 24px; height: 24px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Konfigurasi Nota & Informasi Toko
                </h3>
                <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Informasi ini akan ditampilkan pada cetakan nota penjualan.</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" style="padding: 32px;">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px;">Nama Toko / Perusahaan</label>
                        <input type="text" name="shop_name" value="{{ $settings['shop_name'] }}" required
                               style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; transition: border-color 0.2s;" 
                               placeholder="Contoh: MANAJEMEN KOPI">
                        @error('shop_name') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px;">Email Toko</label>
                        <input type="email" name="shop_email" value="{{ $settings['shop_email'] }}" required
                               style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px;"
                               placeholder="Contoh: hello@kopimanajer.com">
                        @error('shop_email') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px;">No. Telepon / WhatsApp</label>
                    <input type="text" name="shop_phone" value="{{ $settings['shop_phone'] }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px;"
                           placeholder="Contoh: (021) 1234-5678">
                    @error('shop_phone') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px;">Alamat Lengkap</label>
                    <textarea name="shop_address" rows="3" required
                              style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; font-family: inherit;">{{ $settings['shop_address'] }}</textarea>
                    @error('shop_address') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px;">Catatan Kaki (Footer Note)</label>
                    <textarea name="footer_note" rows="3" required
                              style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; font-family: inherit;">{{ $settings['footer_note'] }}</textarea>
                    <p style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Catatan ini akan muncul di bagian paling bawah nota.</p>
                    @error('footer_note') <span style="color:#ef4444;font-size:12px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" 
                            style="background: #0f172a; color: white; border: none; padding: 12px 32px; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="title">Tambah Karyawan Gudang</x-slot>

    <a href="{{ route('admin.warehouse-employees.index') }}" 
       style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 13.5px; font-weight: 700; margin-bottom: 20px; transition: color 0.15s;"
       onmouseover="this.style.color='var(--text-main)'" onmouseout="this.style.color='var(--text-muted)'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar Karyawan
    </a>

    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin: 0;">Tambah Karyawan Gudang</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Tambah data karyawan gudang baru untuk absensi.</p>
    </div>

    @if($errors->any())
        <div style="background:#fff5f5; border:1px solid #fecaca; color:#be123c; padding:12px 16px; border-radius:12px; margin-bottom:20px; font-size:13.5px; font-weight: 600;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background: white; border-radius: 16px; border: 1px solid var(--border); padding: 28px; max-width: 520px; box-shadow: 0 2px 8px rgba(120, 53, 15, 0.02);">
        <form action="{{ route('admin.warehouse-employees.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="name">Nama Karyawan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="name" id="name" required
                       placeholder="Contoh: Budi Santoso"
                       value="{{ old('name') }}"
                       style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                @error('name')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="phone">No HP <span style="color:var(--text-muted); font-weight:400;">(Opsional)</span></label>
                <input type="text" name="phone" id="phone"
                       placeholder="Contoh: 08123456789"
                       value="{{ old('phone') }}"
                       style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                @error('phone')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="note">Catatan <span style="color:var(--text-muted); font-weight:400;">(Opsional)</span></label>
                <textarea name="note" id="note" rows="3"
                          placeholder="Catatan tambahan jika diperlukan."
                          style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('note') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: border-color 0.15s;"
                          onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">{{ old('note') }}</textarea>
                @error('note')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;">Status</label>
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--brown-500);">
                    <span style="font-size: 13.5px; font-weight: 700; color: var(--text-main);">Karyawan aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'">
                    Simpan Karyawan
                </button>
                <a href="{{ route('admin.warehouse-employees.index') }}" 
                   style="flex: 1; padding: 12px; background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s;"
                   onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

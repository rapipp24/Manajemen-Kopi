<x-layouts.admin>
    <x-slot name="title">Edit Satuan</x-slot>

    <div style="max-width: 500px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px;">
        <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 24px;">Edit Data Satuan</h3>
        
        <form action="{{ route('admin.units.update', $unit->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px;">Kode Satuan</label>
                <input type="text" name="code" value="{{ old('code', $unit->code) }}" required maxlength="10"
                       style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('code') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                @error('code') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px;">Nama Satuan</label>
                <input type="text" name="name" value="{{ old('name', $unit->name) }}" required
                       style="width: 100%; padding: 10px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" 
                        style="flex: 1; padding: 12px; background: #92400e; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.units.index') }}" 
                   style="flex: 1; padding: 12px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

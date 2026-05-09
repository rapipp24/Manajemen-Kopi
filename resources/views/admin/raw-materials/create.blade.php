<x-layouts.admin>
    <x-slot name="title">Tambah Bahan Baku</x-slot>

    <div style="max-width: 700px; margin-bottom: 50px;">
        <form action="{{ route('admin.raw-materials.store') }}" method="POST">
            @csrf
            
            <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 18px; background: #92400e; border-radius: 2px;"></span>
                    Informasi Bahan Baku
                </h3>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Nama Bahan</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="Contoh: Biji Kopi Arabika, Gula Pasir, Susu UHT"
                           style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('name') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                    @error('name') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Satuan Stok</label>
                        <select name="unit_id" required 
                                style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('unit_id') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px; background-color: white;">
                            <option value="">Pilih Satuan</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->code }})</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Stok Minimum</label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" required
                               style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('minimum_stock') ? '#ef4444' : '#cbd5e1' }}; border-radius: 8px; font-size: 14px;">
                        <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Sistem akan memberi peringatan jika stok di bawah angka ini.</small>
                        @error('minimum_stock') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 14px; background: #92400e; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer;">
                    Simpan Bahan
                </button>
                <a href="{{ route('admin.raw-materials.index') }}" 
                   style="flex: 1; padding: 14px; background: white; color: #64748b; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; text-align: center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>

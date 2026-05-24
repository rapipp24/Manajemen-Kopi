<x-layouts.admin>
    <x-slot name="title">Tambah Karyawan Gudang</x-slot>

    <style>
        .page-header { margin-bottom: 20px; }
        .page-header h1 { font-size: 20px; font-weight: 700; color: #1c1917; margin: 0; }
        .page-header p  { font-size: 13px; color: #78716c; margin: 4px 0 0 0; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            color: #78716c; text-decoration: none; font-size: 13px; font-weight: 600;
            margin-bottom: 16px;
        }
        .btn-back:hover { color: #1c1917; }

        .form-card {
            background: #fff; border: 1px solid #e7e5e4; border-radius: 12px;
            padding: 28px; max-width: 520px;
        }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 700; color: #44403c; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px; border: 1px solid #d6d3d1; border-radius: 8px;
            font-size: 13.5px; color: #1c1917; background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s; box-sizing: border-box;
        }
        .form-control:focus { border-color: #92400e; outline: none; box-shadow: 0 0 0 3px rgba(146,64,14,0.12); }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }

        .toggle-row { display: flex; align-items: center; gap: 12px; }
        .toggle-label { font-size: 13.5px; color: #1c1917; }

        .btn-submit {
            background: #92400e; color: #fff; border: none; padding: 11px 28px;
            border-radius: 9px; font-size: 13.5px; font-weight: 700; cursor: pointer;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #78350f; }
    </style>

    <a href="{{ route('admin.warehouse-employees.index') }}" class="btn-back">
        ← Kembali ke Daftar Karyawan
    </a>

    <div class="page-header">
        <h1>Tambah Karyawan Gudang</h1>
        <p>Tambahkan data karyawan gudang baru ke master data.</p>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:13px;">
            <ul style="margin:0; padding-left:16px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <form action="{{ route('admin.warehouse-employees.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Karyawan <span style="color:#dc2626;">*</span></label>
                <input type="text" name="name" id="name"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: Budi Santoso"
                       value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">No HP <span style="color:#a8a29e; font-weight:400;">(Opsional)</span></label>
                <input type="text" name="phone" id="phone"
                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: 08123456789"
                       value="{{ old('phone') }}">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Catatan <span style="color:#a8a29e; font-weight:400;">(Opsional)</span></label>
                <textarea name="note" id="note" rows="3"
                          class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}"
                          placeholder="Catatan tambahan jika diperlukan.">{{ old('note') }}</textarea>
                @error('note')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="toggle-row">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                           style="width:18px; height:18px; cursor:pointer; accent-color:#92400e;">
                    <label for="is_active" class="toggle-label">Karyawan aktif</label>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btn-simpan-karyawan">Simpan Karyawan</button>
        </form>
    </div>
</x-layouts.admin>

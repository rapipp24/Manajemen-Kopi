<x-layouts.admin>
    <x-slot name="title">Catat Absensi Karyawan Gudang</x-slot>

    <a href="{{ route('admin.attendances.index') }}" 
       style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 13.5px; font-weight: 700; margin-bottom: 20px; transition: color 0.15s;"
       onmouseover="this.style.color='var(--text-main)'" onmouseout="this.style.color='var(--text-muted)'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 16px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar Absensi
    </a>

    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin: 0;">Catat Absensi Karyawan Gudang</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Catat kehadiran manual karyawan gudang.</p>
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
        <form action="{{ route('admin.attendances.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="warehouse_employee_id">Pilih Karyawan <span style="color:#dc2626;">*</span></label>
                <select name="warehouse_employee_id" id="warehouse_employee_id" required
                        style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('warehouse_employee_id') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; background: white; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    <option value="">-- Pilih Karyawan Gudang --</option>
                    @foreach($warehouseEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ old('warehouse_employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_employee_id')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="attendance_date">Tanggal Absensi <span style="color:#dc2626;">*</span></label>
                <input type="date" name="attendance_date" id="attendance_date" required
                       value="{{ old('attendance_date', date('Y-m-d')) }}"
                       style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('attendance_date') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; outline: none; transition: border-color 0.15s;"
                       onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                @error('attendance_date')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="status">Status Kehadiran <span style="color:#dc2626;">*</span></label>
                <select name="status" id="status" required
                        style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('status') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; background: white; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">
                    <option value="">-- Pilih Status --</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ old('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: var(--text-mid); margin-bottom: 6px;" for="note">
                    Alasan / Catatan
                    <span id="note-required-star" style="color:#dc2626; display:none;">*</span>
                    <span id="note-optional-label" style="color:var(--text-muted); font-weight:400;">(Opsional)</span>
                </label>
                <textarea name="note" id="note" rows="3"
                          placeholder="Masukkan alasan jika izin, atau catatan jika ada."
                          style="width: 100%; padding: 10px 14px; border: 1px solid {{ $errors->has('note') ? '#ef4444' : 'var(--border)' }}; border-radius: 10px; font-size: 14px; resize: vertical; outline: none; transition: border-color 0.15s;"
                          onfocus="this.style.borderColor='var(--brown-500)'" onblur="this.style.borderColor='var(--border)'">{{ old('note') }}</textarea>
                @error('note')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" 
                        style="flex: 2; padding: 12px; background: var(--brown-500); color: white; border: none; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.15s; box-shadow: 0 4px 12px rgba(146, 64, 14, 0.15);"
                        onmouseover="this.style.background='var(--brown-700)'" onmouseout="this.style.background='var(--brown-500)'"
                        id="btn-simpan-absensi">
                    Simpan Absensi
                </button>
                <a href="{{ route('admin.attendances.index') }}" 
                   style="flex: 1; padding: 12px; background: var(--cream-100); color: var(--text-mid); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; text-align: center; transition: all 0.2s;"
                   onmouseover="this.style.background='var(--cream-200)'" onmouseout="this.style.background='var(--cream-100)'">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const requiredStar = document.getElementById('note-required-star');
            const optionalLabel = document.getElementById('note-optional-label');
            const noteTextarea = document.getElementById('note');

            function toggleNoteRequirement() {
                if (statusSelect.value === 'izin') {
                    requiredStar.style.display = 'inline';
                    optionalLabel.style.display = 'none';
                    noteTextarea.setAttribute('required', 'required');
                    noteTextarea.placeholder = 'Wajib mengisi alasan izin...';
                } else {
                    requiredStar.style.display = 'none';
                    optionalLabel.style.display = 'inline';
                    noteTextarea.removeAttribute('required');
                    noteTextarea.placeholder = 'Masukkan alasan jika izin, atau catatan jika ada.';
                }
            }

            statusSelect.addEventListener('change', toggleNoteRequirement);
            // Run on load to set initial state based on old input
            toggleNoteRequirement();
        });
    </script>
</x-layouts.admin>

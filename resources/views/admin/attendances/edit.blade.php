<x-layouts.admin>
    <x-slot name="title">Edit Absensi Karyawan Gudang</x-slot>

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

        .btn-submit {
            background: #92400e; color: #fff; border: none; padding: 11px 28px;
            border-radius: 9px; font-size: 13.5px; font-weight: 700; cursor: pointer;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #78350f; }
    </style>

    <a href="{{ route('admin.attendances.index') }}" class="btn-back">
        ← Kembali ke Daftar Absensi
    </a>

    <div class="page-header">
        <h1>Edit Absensi Karyawan Gudang</h1>
        <p>Perbarui data absensi yang telah dicatat.</p>
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
        <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="warehouse_employee_id">Karyawan <span style="color:#dc2626;">*</span></label>
                <select name="warehouse_employee_id" id="warehouse_employee_id"
                        class="form-control {{ $errors->has('warehouse_employee_id') ? 'is-invalid' : '' }}" required>
                    @foreach($warehouseEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ old('warehouse_employee_id', $attendance->warehouse_employee_id) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} {{ !$emp->is_active ? '(Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_employee_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="attendance_date">Tanggal Absensi <span style="color:#dc2626;">*</span></label>
                <input type="date" name="attendance_date" id="attendance_date"
                       class="form-control {{ $errors->has('attendance_date') ? 'is-invalid' : '' }}"
                       value="{{ old('attendance_date', $attendance->attendance_date ? $attendance->attendance_date->format('Y-m-d') : '') }}" required>
                @error('attendance_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Kehadiran <span style="color:#dc2626;">*</span></label>
                <select name="status" id="status"
                        class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ old('status', $attendance->status) === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="note">
                    Alasan / Catatan
                    <span id="note-required-star" style="color:#dc2626; display:none;">*</span>
                    <span id="note-optional-label" style="color:#a8a29e; font-weight:400;">(Opsional)</span>
                </label>
                <textarea name="note" id="note" rows="3"
                          class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}"
                          placeholder="Masukkan alasan jika izin, atau catatan jika ada.">{{ old('note', $attendance->note) }}</textarea>
                @error('note')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="btn-update-absensi">Perbarui Absensi</button>
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
            // Run on load to set initial state based on current model/old values
            toggleNoteRequirement();
        });
    </script>
</x-layouts.admin>

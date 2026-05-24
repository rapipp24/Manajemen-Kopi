<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attendanceId = $this->route('attendance')?->id ?? $this->route('attendance');

        return [
            'warehouse_employee_id' => [
                'required',
                'integer',
                'exists:warehouse_employees,id',
            ],
            'attendance_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($attendanceId) {
                    $exists = \Illuminate\Support\Facades\DB::table('employee_attendances')
                        ->where('warehouse_employee_id', $this->input('warehouse_employee_id'))
                        ->where('id', '!=', $attendanceId)
                        ->where(function ($q) use ($value) {
                            $q->whereDate('attendance_date', $value)
                              ->orWhere('attendance_date', $value);
                        })
                        ->exists();
                    if ($exists) {
                        $fail('Absensi karyawan ini pada tanggal tersebut sudah tercatat.');
                    }
                },
            ],
            'status' => ['required', 'in:hadir,izin,sakit,alfa'],
            'note'   => ['required_if:status,izin', 'nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_employee_id.required' => 'Karyawan wajib dipilih.',
            'warehouse_employee_id.exists'   => 'Karyawan tidak ditemukan.',
            'attendance_date.required'       => 'Tanggal absensi wajib diisi.',
            'attendance_date.date'           => 'Format tanggal tidak valid.',
            'status.required'               => 'Status kehadiran wajib dipilih.',
            'status.in'                     => 'Status tidak valid. Pilih: Hadir, Izin, Sakit, atau Alfa.',
            'note.required_if'              => 'Alasan wajib diisi jika status kehadiran adalah Izin.',
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\WarehouseEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendances (Daily Board / Papan Absensi Harian).
     */
    public function index(Request $request)
    {
        // 1. Tentukan tanggal terpilih (default: hari ini)
        $selectedDate = $request->input('date', date('Y-m-d'));

        // 2. Ambil semua karyawan aktif saat ini
        $activeEmployees = WarehouseEmployee::where('is_active', true)->orderBy('name')->get();

        // 3. Ambil seluruh record absensi pada tanggal terpilih
        $attendances = EmployeeAttendance::with(['warehouseEmployee', 'creator'])
            ->whereDate('attendance_date', $selectedDate)
            ->get()
            ->keyBy('warehouse_employee_id');

        // 4. Ambil karyawan nonaktif yang memiliki record absensi pada tanggal tersebut
        $inactiveWithAttendance = collect();
        foreach ($attendances as $att) {
            $emp = $att->warehouseEmployee;
            if ($emp && !$emp->is_active) {
                $inactiveWithAttendance->push($emp);
            }
        }

        // 5. Gabungkan karyawan aktif dengan karyawan nonaktif yang memiliki record pada tanggal ini
        // Agar data lama tetap tampil utuh di riwayat
        $employees = $activeEmployees->concat($inactiveWithAttendance)->unique('id')->sortBy('name')->values();

        // 6. Hitung Summary Statistics untuk tanggal terpilih
        $totalActive = $activeEmployees->count();
        
        $countHadir = 0;
        $countIzin = 0;
        $countSakit = 0;
        $countAlfa = 0;
        $activeWithAttendanceCount = 0;

        foreach ($employees as $emp) {
            $att = $attendances->get($emp->id);
            if ($att) {
                if ($att->status === 'hadir') {
                    $countHadir++;
                } elseif ($att->status === 'izin') {
                    $countIzin++;
                } elseif ($att->status === 'sakit') {
                    $countSakit++;
                } elseif ($att->status === 'alfa') {
                    $countAlfa++;
                }

                if ($emp->is_active) {
                    $activeWithAttendanceCount++;
                }
            }
        }

        // Belum Dicatat = Total Karyawan Aktif - Karyawan Aktif yang sudah punya absensi
        $countBelumDicatat = max(0, $totalActive - $activeWithAttendanceCount);

        $summary = [
            'total_active' => $totalActive,
            'hadir' => $countHadir,
            'izin' => $countIzin,
            'sakit' => $countSakit,
            'alfa' => $countAlfa,
            'belum_dicatat' => $countBelumDicatat,
        ];

        $statuses = EmployeeAttendance::$statuses;

        return view('admin.attendances.index', compact('employees', 'attendances', 'selectedDate', 'summary', 'statuses'));
    }

    /**
     * Mark or update attendance status for a specific warehouse employee and date.
     */
    public function mark(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'warehouse_employee_id' => ['required', 'integer', 'exists:warehouse_employees,id'],
            'attendance_date'       => ['required', 'date'],
            'status'                => ['required', 'in:hadir,izin,sakit,alfa'],
            'note'                  => ['nullable', 'string', 'max:1000'],
        ], [
            'warehouse_employee_id.required' => 'Karyawan wajib dipilih.',
            'warehouse_employee_id.exists'   => 'Karyawan tidak ditemukan.',
            'attendance_date.required'       => 'Tanggal absensi wajib diisi.',
            'attendance_date.date'           => 'Format tanggal tidak valid.',
            'status.required'                => 'Status kehadiran wajib dipilih.',
            'status.in'                      => 'Status tidak valid. Pilih: Hadir, Izin, Sakit, atau Alfa.',
        ]);

        // Cek validasi note jika status = izin
        $validator->after(function ($validator) use ($request) {
            if ($request->input('status') === 'izin' && !trim($request->input('note'))) {
                $validator->errors()->add('note', 'Alasan wajib diisi jika status kehadiran adalah Izin.');
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employeeId = $request->input('warehouse_employee_id');
        $date = $request->input('attendance_date');
        $status = $request->input('status');
        $note = $request->input('note');

        // 2. Ambil karyawan & periksa keaktifan
        $employee = WarehouseEmployee::findOrFail($employeeId);
        $existingRecord = EmployeeAttendance::where('warehouse_employee_id', $employeeId)
            ->whereDate('attendance_date', $date)
            ->first();

        // Karyawan nonaktif: tidak boleh dicatat untuk absensi baru jika belum ada record pada tanggal tersebut
        if (!$employee->is_active && !$existingRecord) {
            return redirect()
                ->back()
                ->with('error', 'Karyawan ini sudah nonaktif dan tidak bisa dicatat untuk absensi baru.');
        }

        // Aturan Note: jika status berubah dari izin ke hadir/sakit/alfa, kosongkan note lama agar tidak tertinggal
        // Jika status = hadir, note harus kosong/null
        if ($status === 'hadir') {
            $note = null;
        }

        // 3. Simpan data absensi secara aman (menghindari issue format tanggal SQLite)
        if ($existingRecord) {
            $existingRecord->update([
                'status' => $status,
                'note'   => $note,
            ]);
        } else {
            EmployeeAttendance::create([
                'warehouse_employee_id' => $employeeId,
                'attendance_date'       => $date,
                'status'                => $status,
                'note'                  => $note,
                'created_by'            => Auth::id(),
            ]);
        }

        return redirect()
            ->route('admin.attendances.index', ['date' => $date])
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Show the form for creating a new attendance.
     * (Dipertahankan untuk manual correction/cadangan jika diperlukan)
     */
    public function create()
    {
        $warehouseEmployees = WarehouseEmployee::active()->orderBy('name')->get();
        $statuses = EmployeeAttendance::$statuses;

        return view('admin.attendances.create', compact('warehouseEmployees', 'statuses'));
    }

    /**
     * Store a newly created attendance.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_employee_id' => ['required', 'exists:warehouse_employees,id'],
            'attendance_date'       => ['required', 'date'],
            'status'                => ['required', 'in:hadir,izin,sakit,alfa'],
            'note'                  => ['required_if:status,izin', 'nullable', 'string', 'max:1000'],
        ]);

        EmployeeAttendance::create([
            ...$request->all(),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'Absensi berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit(EmployeeAttendance $attendance)
    {
        $savedEmployee = $attendance->warehouseEmployee;
        $activeEmployees = WarehouseEmployee::active()->orderBy('name')->get();

        if ($savedEmployee && !$savedEmployee->is_active) {
            $warehouseEmployees = $activeEmployees->push($savedEmployee)->sortBy('name')->values();
        } else {
            $warehouseEmployees = $activeEmployees;
        }

        $statuses = EmployeeAttendance::$statuses;

        return view('admin.attendances.edit', compact('attendance', 'warehouseEmployees', 'statuses'));
    }

    /**
     * Update the specified attendance.
     */
    public function update(Request $request, EmployeeAttendance $attendance)
    {
        $request->validate([
            'warehouse_employee_id' => ['required', 'exists:warehouse_employees,id'],
            'attendance_date'       => ['required', 'date'],
            'status'                => ['required', 'in:hadir,izin,sakit,alfa'],
            'note'                  => ['required_if:status,izin', 'nullable', 'string', 'max:1000'],
        ]);

        $attendance->update($request->all());

        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified attendance.
     */
    public function destroy(EmployeeAttendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }
}

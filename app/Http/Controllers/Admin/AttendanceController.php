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

    /**
     * Helper to compute attendance recap data for a month and year.
     */
    private function getRecapData($month, $year)
    {
        // 1. Ambil karyawan aktif saat ini
        $activeEmployees = WarehouseEmployee::where('is_active', true)->orderBy('name')->get();

        // 2. Ambil karyawan nonaktif yang memiliki record absensi pada bulan/tahun terpilih
        $inactiveWithAttendanceIds = EmployeeAttendance::whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->pluck('warehouse_employee_id')
            ->unique();

        $inactiveEmployees = WarehouseEmployee::where('is_active', false)
            ->whereIn('id', $inactiveWithAttendanceIds)
            ->orderBy('name')
            ->get();

        // Gabungkan karyawan aktif dengan karyawan nonaktif yang memiliki data pada bulan terpilih
        $employees = $activeEmployees->concat($inactiveEmployees)->unique('id')->sortBy('name')->values();

        // 3. Ambil seluruh data absensi untuk bulan/tahun terpilih
        $attendances = EmployeeAttendance::whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get()
            ->groupBy('warehouse_employee_id');

        // 4. Hitung jumlah hari dalam bulan terpilih
        $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // 5. Hitung daysToCount untuk hitung "Belum Dicatat" sesuai guardrail:
        // - bulan berjalan: hitung hanya sampai hari ini
        // - bulan lampau: hitung sampai akhir bulan
        // - bulan masa depan: jangan dihitung sebagai belum dicatat (0)
        $todayDay = (int)date('j');
        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        if ($year == $currentYear && $month == $currentMonth) {
            $daysToCount = $todayDay;
        } elseif ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            $daysToCount = $daysInMonth;
        } else {
            $daysToCount = 0;
        }

        $recap = [];
        $totals = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alfa' => 0,
            'belum_dicatat' => 0,
        ];

        foreach ($employees as $emp) {
            $empAttendances = $attendances->get($emp->id, collect());
            $hadir = 0;
            $izin = 0;
            $sakit = 0;
            $alfa = 0;

            foreach ($empAttendances as $att) {
                if ($att->status === 'hadir') {
                    $hadir++;
                } elseif ($att->status === 'izin') {
                    $izin++;
                } elseif ($att->status === 'sakit') {
                    $sakit++;
                } elseif ($att->status === 'alfa') {
                    $alfa++;
                }
            }

            $totalRecorded = $hadir + $izin + $sakit + $alfa;
            
            // "Belum Dicatat" dihitung aman dari negative value
            $belumDicatat = max(0, $daysToCount - $totalRecorded);

            $recap[] = [
                'employee' => $emp,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'belum_dicatat' => $belumDicatat,
            ];

            // Tambahkan ke total keseluruhan
            $totals['hadir'] += $hadir;
            $totals['izin'] += $izin;
            $totals['sakit'] += $sakit;
            $totals['alfa'] += $alfa;
            $totals['belum_dicatat'] += $belumDicatat;
        }

        return [$employees, $recap, $totals, $daysInMonth];
    }

    /**
     * Tampilkan halaman rekap absensi bulanan.
     */
    public function recap(Request $request)
    {
        $month = (int)$request->input('month', date('n'));
        $year = (int)$request->input('year', date('Y'));

        list($employees, $recap, $totals, $daysInMonth) = $this->getRecapData($month, $year);

        // Daftar nama bulan dalam Bahasa Indonesia
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Daftar pilihan tahun (dari 2024 sampai tahun berjalan + 1)
        $startYear = 2024;
        $endYear = (int)date('Y') + 1;
        $years = range($startYear, $endYear);

        return view('admin.reports.attendance', compact('recap', 'totals', 'month', 'year', 'months', 'years', 'daysInMonth'));
    }

    /**
     * Cetak rekap absensi bulanan.
     */
    public function recapPrint(Request $request)
    {
        $month = (int)$request->input('month', date('n'));
        $year = (int)$request->input('year', date('Y'));

        list($employees, $recap, $totals, $daysInMonth) = $this->getRecapData($month, $year);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.reports.attendance_print', compact('recap', 'totals', 'month', 'year', 'months', 'daysInMonth'));
    }

    /**
     * Export rekap absensi bulanan ke file CSV.
     */
    public function recapExport(Request $request)
    {
        $month = (int)$request->input('month', date('n'));
        $year = (int)$request->input('year', date('Y'));

        list($employees, $recap, $totals, $daysInMonth) = $this->getRecapData($month, $year);

        // Format nama bulan agar pad 2 digit untuk nama file
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
        $filename = "rekap-absensi-{$year}-{$monthPadded}.csv";

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $months[$month];

        $callback = function() use ($recap, $totals, $monthName, $year) {
            $file = fopen('php://output', 'w');
            
            // Tulis UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header info rekap
            fputcsv($file, ["REKAP ABSENSI BULANAN KARYAWAN GUDANG"], ';');
            fputcsv($file, ["Periode: {$monthName} {$year}"], ';');
            fputcsv($file, ["Tanggal Cetak: " . date('d-m-Y H:i')], ';');
            fputcsv($file, [], ';');

            // Header Tabel
            fputcsv($file, ['No', 'Nama Karyawan', 'Status Aktif', 'Hadir', 'Izin', 'Sakit', 'Alfa', 'Belum Dicatat'], ';');

            // Baris Data Karyawan
            $no = 1;
            foreach ($recap as $row) {
                fputcsv($file, [
                    $no++,
                    $row['employee']->name,
                    $row['employee']->is_active ? 'Aktif' : 'Nonaktif',
                    $row['hadir'],
                    $row['izin'],
                    $row['sakit'],
                    $row['alfa'],
                    $row['belum_dicatat']
                ], ';');
            }

            // Baris Total Keseluruhan
            fputcsv($file, [], ';');
            fputcsv($file, [
                'TOTAL',
                '',
                '',
                $totals['hadir'],
                $totals['izin'],
                $totals['sakit'],
                $totals['alfa'],
                $totals['belum_dicatat']
            ], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

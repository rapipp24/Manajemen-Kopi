<?php

namespace App\Services;

use App\Models\WarehouseEmployee;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;

class AttendanceRecapService
{
    /**
     * Compute attendance recap data for a specific month and year.
     *
     * @param int $month
     * @param int $year
     * @return array [$employees, $recap, $totals, $daysInMonth]
     */
    public function getRecapData(int $month, int $year): array
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
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

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
}

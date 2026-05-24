<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAttendance extends Model
{
    protected $fillable = [
        'warehouse_employee_id',
        'attendance_date',
        'status',
        'note',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
        ];
    }

    /**
     * Karyawan gudang yang diabsensi.
     */
    public function warehouseEmployee(): BelongsTo
    {
        return $this->belongsTo(WarehouseEmployee::class, 'warehouse_employee_id');
    }

    /**
     * Admin yang mencatat absensi.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Label status dalam Bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alfa'  => 'Alfa',
            default => ucfirst($this->status),
        };
    }

    /**
     * Konstanta status yang valid.
     */
    public static array $statuses = ['hadir', 'izin', 'sakit', 'alfa'];
}

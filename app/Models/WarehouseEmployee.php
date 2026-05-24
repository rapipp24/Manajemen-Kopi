<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseEmployee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'note',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Daftar absensi karyawan gudang ini.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class, 'warehouse_employee_id');
    }

    /**
     * Scope untuk karyawan yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

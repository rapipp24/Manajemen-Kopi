<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageAssembly extends Model
{
    protected $fillable = [
        'assembly_number',
        'package_id',
        'qty',
        'hpp_per_package_snapshot',
        'note',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'float',
        'hpp_per_package_snapshot' => 'float',
    ];

    /**
     * Relasi ke master paket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }

    /**
     * Relasi ke detail produk penyusun yang dikonsumsi.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PackageAssemblyItem::class, 'package_assembly_id');
    }

    /**
     * Relasi ke admin pembuat transaksi.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate assembly number unik secara aman: ASM-YYYYMMDD-XXXX.
     */
    public static function generateAssemblyNumber(): string
    {
        $date = date('Ymd');
        $lastAssembly = self::whereDate('created_at', today())
            ->orderBy('assembly_number', 'desc')
            ->first();

        if (!$lastAssembly) {
            return 'ASM-' . $date . '-0001';
        }

        $lastNumber = $lastAssembly->assembly_number;
        $numStr = substr($lastNumber, -4);
        
        if (is_numeric($numStr)) {
            $nextNum = (int)$numStr + 1;
        } else {
            $nextNum = 1;
        }

        return 'ASM-' . $date . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PackageStockMovement extends Model
{
    protected $fillable = [
        'package_id',
        'user_id',
        'movement_type',
        'qty',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'float',
        'stock_before' => 'float',
        'stock_after' => 'float',
    ];

    /**
     * Relasi ke master paket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }

    /**
     * Relasi ke sales (jika movement berada di bawah stok sales).
     * Bernilai NULL jika milik gudang utama.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke admin pembuat transaksi/movement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi polimorfik ke dokumen/referensi transaksi pemicu mutasi.
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}

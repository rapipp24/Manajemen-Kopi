<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageStock extends Model
{
    protected $fillable = [
        'package_id',
        'qty',
    ];

    protected $casts = [
        'qty' => 'float',
    ];

    /**
     * Relasi ke master paket.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}

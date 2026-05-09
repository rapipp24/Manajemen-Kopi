<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterial extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code', 'name', 'unit_id', 'minimum_stock', 'current_stock', 'is_active'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}

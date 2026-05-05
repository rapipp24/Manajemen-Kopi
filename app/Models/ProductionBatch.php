<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'production_date',
        'product_type',
        'total_material_used',
        'total_output',
        'shrinkage',
        'note',
        'created_by'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ProductionBatchItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

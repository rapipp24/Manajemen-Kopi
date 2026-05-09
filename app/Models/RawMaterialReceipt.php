<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterialReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'reference_number',
        'supplier_id',
        'receipt_date',
        'note',
        'total_amount',
        'created_by'
    ];

    public function getRouteKeyName()
    {
        return 'receipt_number';
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RawMaterialReceiptItem::class, 'receipt_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingItem extends Model
{
    protected $fillable = [
        'packing_transaction_id',
        'product_id',
        'qty_pack',
        'weight_per_pack',
        'total_weight'
    ];

    public function packingTransaction(): BelongsTo
    {
        return $this->belongsTo(PackingTransaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}

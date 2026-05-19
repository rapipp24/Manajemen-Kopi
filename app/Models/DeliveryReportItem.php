<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryReportItem extends Model
{
    protected $fillable = [
        'delivery_report_id',
        'product_id',
        'qty',
        'price',
        'subtotal',
    ];

    public function deliveryReport(): BelongsTo
    {
        return $this->belongsTo(DeliveryReport::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

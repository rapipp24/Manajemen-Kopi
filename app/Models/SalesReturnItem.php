<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesReturnItem extends Model
{
    protected $fillable = [
        'sales_return_id',
        'delivery_report_item_id',
        'product_id',
        'qty_return',
        'price_snapshot',
        'subtotal_return',
        'reason',
    ];

    protected $casts = [
        'price_snapshot'  => 'decimal:2',
        'subtotal_return' => 'decimal:2',
    ];

    /** Return induk */
    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class);
    }

    /** Item delivery report yang dijadikan referensi (untuk validasi qty & harga) */
    public function deliveryReportItem(): BelongsTo
    {
        return $this->belongsTo(DeliveryReportItem::class);
    }

    /** Produk yang dikembalikan */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

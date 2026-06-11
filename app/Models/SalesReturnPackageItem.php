<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesReturnPackageItem extends Model
{
    protected $fillable = [
        'sales_return_id',
        'delivery_report_package_item_id',
        'package_id',
        'qty',
        'price',
        'subtotal',
        'package_name_snapshot',
        'package_code_snapshot',
        'package_hpp_snapshot',
        'condition',
        'replacement_note',
        'admin_note',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'package_hpp_snapshot' => 'decimal:2',
    ];

    public function salesReturn(): BelongsTo
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function deliveryReportPackageItem(): BelongsTo
    {
        return $this->belongsTo(DeliveryReportPackageItem::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }
}

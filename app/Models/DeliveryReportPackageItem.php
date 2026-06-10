<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryReportPackageItem extends Model
{
    protected $fillable = [
        'delivery_report_id',
        'package_id',
        'qty',
        'price',
        'subtotal',
        'package_name_snapshot',
        'package_code_snapshot',
        'package_hpp_snapshot',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'package_hpp_snapshot' => 'decimal:2',
    ];

    public function deliveryReport(): BelongsTo
    {
        return $this->belongsTo(DeliveryReport::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }
}

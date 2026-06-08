<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAssemblyItem extends Model
{
    protected $fillable = [
        'package_assembly_id',
        'product_id',
        'qty_per_package',
        'total_qty_used',
        'cost_price_snapshot',
    ];

    protected $casts = [
        'qty_per_package' => 'float',
        'total_qty_used' => 'float',
        'cost_price_snapshot' => 'float',
    ];

    /**
     * Relasi ke transaksi perakitan parent.
     */
    public function assembly(): BelongsTo
    {
        return $this->belongsTo(PackageAssembly::class, 'package_assembly_id');
    }

    /**
     * Relasi ke produk komponen.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}

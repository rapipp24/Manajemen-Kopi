<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterialReceiptItem extends Model
{
    protected $fillable = [
        'receipt_id',
        'raw_material_id',
        'qty',
        'unit_price',
        'subtotal'
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(RawMaterialReceipt::class, 'receipt_id');
    }

    public function rawMaterial(): BelongsTo
    {
        // withTrashed() agar item penerimaan tetap bisa menampilkan bahan baku
        // yang sudah soft-deleted, menjaga integritas histori transaksi.
        return $this->belongsTo(RawMaterial::class)->withTrashed();
    }
}

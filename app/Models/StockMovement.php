<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'item_type',
        'item_id',
        'movement_type',
        'reference_type',
        'reference_id',
        'qty',
        'stock_before',
        'stock_after',
        'note',
        'user_id',   // nullable, diisi jika movement terkait stok sales
    ];

    /** Sales terkait (jika movement dari stok sales) */
    public function sales()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent item model (Product or RawMaterial).
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the parent reference model (Receipt, Batch, Sale, etc.).
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesReturn extends Model
{
    protected $fillable = [
        'return_number',
        'delivery_report_id',
        'sales_id',
        'return_date',
        'status',
        'return_condition',
        'note',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'return_type',
    ];

    public function isTukarBarang(): bool
    {
        return $this->return_type === 'tukar_barang';
    }

    public function isPotongTagihan(): bool
    {
        return $this->return_type === 'potong_tagihan';
    }

    protected $casts = [
        'return_date'  => 'date',
        'approved_at'  => 'datetime',
    ];

    /** Sales yang mengajukan return */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    /** Laporan pengiriman terkait */
    public function deliveryReport(): BelongsTo
    {
        return $this->belongsTo(DeliveryReport::class);
    }

    /** Admin yang memproses (approve/tolak) */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** Item-item produk yang dikembalikan */
    public function items(): HasMany
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    /**
     * Total nilai return ini (penjumlahan semua subtotal item).
     */
    public function getTotalReturnAttribute(): float
    {
        return (float) $this->items->sum('subtotal_return');
    }
}

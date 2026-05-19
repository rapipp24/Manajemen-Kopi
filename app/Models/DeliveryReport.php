<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryReport extends Model
{
    protected $fillable = [
        'report_number',
        'sales_id',
        'customer_id',               // nullable — dari master customer (opsional)
        'customer_name_manual',      // wajib jika customer_id kosong
        'customer_address_manual',   // opsional
        'customer_phone_manual',     // opsional
        'payment_term_days',         // tempo: 15 | 30 | null
        'delivery_date',
        'note',
        'status',
        'created_by',
    ];

    protected $casts = [
        'delivery_date'    => 'date',
        'payment_term_days' => 'integer',
    ];

    /**
     * Nama toko tujuan — prioritaskan master customer,
     * fallback ke input manual. Berguna di view agar tidak perlu if/else berulang.
     */
    public function getTokoNameAttribute(): string
    {
        return $this->customer?->name ?? $this->customer_name_manual ?? '—';
    }

    /** Sales yang mengirim */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    /** Toko dari master customer (nullable) */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** Admin/sales yang mencatat */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Detail produk yang dikirim */
    public function items(): HasMany
    {
        return $this->hasMany(DeliveryReportItem::class);
    }
}

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
        'customer_id',
        'customer_name_manual',
        'customer_address_manual',
        'customer_phone_manual',
        'payment_term_days',
        'delivery_date',
        'note',
        'status',
        'total_amount',
        'payment_status',
        'down_payment_amount',
        'due_date',
        'created_by',
    ];

    protected $casts = [
        'delivery_date'    => 'date',
        'due_date'         => 'date',
        'payment_term_days' => 'integer',
        'total_amount'     => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
    ];

    /**
     * Hitung sisa tagihan
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->down_payment_amount);
    }

    public function deposits()
    {
        return $this->hasMany(SalesDeposit::class, 'delivery_report_id');
    }

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

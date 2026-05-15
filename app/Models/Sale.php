<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'sale_date',
        'payment_status',
        'payment_method',
        'total_amount',
        'note',
        'created_by'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    // Helper untuk hitung total yang sudah dibayar
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    // Helper untuk hitung sisa tagihan
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

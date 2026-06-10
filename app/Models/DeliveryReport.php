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
        'overpayment_resolved_at',
        'overpayment_resolved_by',
        'overpayment_resolution_note',
    ];

    protected $casts = [
        'delivery_date'    => 'date',
        'due_date'         => 'date',
        'payment_term_days' => 'integer',
        'total_amount'     => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'overpayment_resolved_at' => 'datetime',
    ];

    /**
     * Hitung sisa tagihan
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_amount - $this->down_payment_amount);
    }

    /**
     * Hitung nominal bayar lebih jika ada
     */
    public function getOverpaymentAmountAttribute(): float
    {
        $tagihanEfektif = $this->tagihan_efektif;
        $totalBayar = (float) $this->down_payment_amount;
        if ($totalBayar > $tagihanEfektif) {
            return $totalBayar - $tagihanEfektif;
        }
        return 0.0;
    }

    /**
     * Cek apakah ada bayar lebih
     */
    public function getIsOverpaidAttribute(): bool
    {
        return $this->overpayment_amount > 0;
    }

    /**
     * Status bayar lebih
     */
    public function getOverpaymentStatusAttribute(): string
    {
        if (!$this->is_overpaid) {
            return 'none';
        }
        return $this->overpayment_resolved_at ? 'selesai' : 'belum_selesai';
    }

    /**
     * User admin yang menandai penyelesaian bayar lebih
     */
    public function overpaymentResolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'overpayment_resolved_by');
    }

    /**
     * Total return yang sudah diterima admin pada laporan ini.
     * Dihitung dinamis dari relasi, tidak disimpan di kolom.
     */
    public function getTotalReturnDiterimaAttribute(): float
    {
        return (float) $this->salesReturns()
            ->where('sales_returns.status', 'diterima')
            ->where('sales_returns.return_type', 'potong_tagihan')
            ->join('sales_return_items', 'sales_returns.id', '=', 'sales_return_items.sales_return_id')
            ->sum('sales_return_items.subtotal_return');
    }

    /**
     * Tagihan efektif = total_amount dikurangi semua return yang sudah diterima.
     */
    public function getTagihanEfektifAttribute(): float
    {
        return max(0, (float) $this->total_amount - $this->total_return_diterima);
    }

    /**
     * Nilai total return berstatus diterima.
     */
    public function getAcceptedReturnAmountAttribute(): float
    {
        return $this->total_return_diterima;
    }

    /**
     * Nilai tagihan efektif = total_amount - accepted_return_amount.
     */
    public function getEffectiveTotalAmountAttribute(): float
    {
        return $this->tagihan_efektif;
    }

    /**
     * Sisa tagihan efektif = max(0, effective_total_amount - down_payment_amount).
     */
    public function getEffectiveRemainingAmountAttribute(): float
    {
        return max(0.0, $this->effective_total_amount - (float) $this->down_payment_amount);
    }

    /**
     * Sinkronisasi payment_status di database secara aman.
     */
    public function syncPaymentStatus(): bool
    {
        $remaining = $this->effective_remaining_amount;
        $dp = (float) $this->down_payment_amount;

        $status = 'belum_bayar';
        if ($remaining <= 0.0) {
            $status = 'lunas';
        } elseif ($dp > 0.0 && $remaining > 0.0) {
            $status = 'dp';
        } elseif ($dp <= 0.0 && $remaining > 0.0) {
            $status = 'belum_bayar';
        }

        return $this->update(['payment_status' => $status]);
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

    /** Detail paket yang dikirim */
    public function packageItems(): HasMany
    {
        return $this->hasMany(DeliveryReportPackageItem::class);
    }

    /** Return barang yang diajukan dari laporan ini */
    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }
}

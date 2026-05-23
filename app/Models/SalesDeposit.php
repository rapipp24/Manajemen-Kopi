<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDeposit extends Model
{
    protected $fillable = [
        'deposit_number',
        'delivery_report_id',
        'sales_id',
        'amount',
        'payment_date',
        'payment_method',
        'payment_proof_path',
        'note',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function deliveryReport()
    {
        return $this->belongsTo(DeliveryReport::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

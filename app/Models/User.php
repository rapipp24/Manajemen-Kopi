<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_SALES = 'sales';

    const APPROVAL_PENDING  = 'pending';
    const APPROVAL_APPROVED = 'approved';
    const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'google_id',
        'google_token',
        // Approval fields
        'approval_status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejection_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'approved_at'       => 'datetime',
            'rejected_at'       => 'datetime',
        ];
    }

    // ── Role helpers ────────────────────────────────────────────────────────

    /** Cek apakah user adalah admin */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Cek apakah user adalah sales */
    public function isSales(): bool
    {
        return $this->role === self::ROLE_SALES;
    }

    // ── Approval helpers ────────────────────────────────────────────────────

    /** Cek apakah status approval sudah approved */
    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    /** Cek apakah status approval masih pending */
    public function isPending(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    /** Cek apakah status approval sudah rejected */
    public function isRejected(): bool
    {
        return $this->approval_status === self::APPROVAL_REJECTED;
    }

    /**
     * Cek apakah user boleh mengakses portal aplikasi.
     * Syarat: is_active = true DAN approval_status = approved.
     * Verifikasi email tidak dicek di sini karena sudah dihandle
     * oleh middleware 'verified' di route dan di LoginRequest.
     */
    public function canAccessApplication(): bool
    {
        return $this->is_active === true
            && $this->approval_status === self::APPROVAL_APPROVED;
    }

    // ── Relationships ───────────────────────────────────────────────────────

    /** Admin yang menyetujui akun ini */
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

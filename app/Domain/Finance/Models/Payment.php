<?php

namespace App\Domain\Finance\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use App\Support\Enums\PaymentMethod;
use App\Support\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends BaseModel
{
    protected $fillable = [
        'payable_type', 'payable_id', 'member_id', 'amount', 'method',
        'gateway_name', 'gateway_ref', 'payment_proof', 'bank_name',
        'account_name', 'account_number', 'status', 'paid_at',
        'verified_by', 'verified_at', 'notes', 'rejection_reason',
        'gateway_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
            'status' => PaymentStatus::class,
            'method' => PaymentMethod::class,
            'gateway_response' => 'json',
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', PaymentStatus::Pending);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', PaymentStatus::Verified);
    }

    public function isVerified(): bool
    {
        return $this->status === PaymentStatus::Verified;
    }
}

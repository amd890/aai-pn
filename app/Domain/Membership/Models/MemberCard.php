<?php

namespace App\Domain\Membership\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberCard extends BaseModel
{
    protected $fillable = [
        'member_id', 'card_number', 'qr_code', 'qr_data',
        'template', 'issued_at', 'expired_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->isExpired();
    }
}

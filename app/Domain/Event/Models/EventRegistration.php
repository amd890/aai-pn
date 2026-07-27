<?php

namespace App\Domain\Event\Models;

use App\Domain\Finance\Models\Payment;
use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventRegistration extends BaseModel
{
    protected $fillable = [
        'event_id', 'member_id', 'status', 'payment_id',
        'registered_at', 'confirmed_at', 'cancelled_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
    public function attendance(): HasOne { return $this->hasOne(EventAttendance::class); }
    public function certificate(): HasOne { return $this->hasOne(EventCertificate::class); }

    public function scopeConfirmed($query) { return $query->where('status', 'confirmed'); }
    public function scopeWaitlist($query) { return $query->where('status', 'waitlist'); }
}

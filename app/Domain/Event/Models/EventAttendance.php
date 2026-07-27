<?php

namespace App\Domain\Event\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendance extends BaseModel
{
    protected $fillable = [
        'event_registration_id', 'qr_code', 'checkin_at',
        'checkout_at', 'checkin_method', 'checked_by',
    ];

    protected function casts(): array
    {
        return [
            'checkin_at' => 'datetime',
            'checkout_at' => 'datetime',
        ];
    }

    public function registration(): BelongsTo { return $this->belongsTo(EventRegistration::class, 'event_registration_id'); }

    public function hasCheckedIn(): bool { return ! is_null($this->checkin_at); }
}

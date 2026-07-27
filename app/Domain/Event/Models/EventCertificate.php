<?php

namespace App\Domain\Event\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCertificate extends BaseModel
{
    protected $fillable = [
        'event_registration_id', 'certificate_number',
        'qr_code', 'file_path', 'issued_at',
    ];

    protected function casts(): array
    {
        return ['issued_at' => 'datetime'];
    }

    public function registration(): BelongsTo { return $this->belongsTo(EventRegistration::class, 'event_registration_id'); }
}

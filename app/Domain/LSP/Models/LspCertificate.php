<?php

namespace App\Domain\LSP\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LspCertificate extends BaseModel
{
    protected $fillable = [
        'participant_id', 'scheme_id', 'certificate_number',
        'qr_code', 'issued_at', 'expired_at', 'file_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function participant(): BelongsTo { return $this->belongsTo(CertificationParticipant::class, 'participant_id'); }
    public function scheme(): BelongsTo { return $this->belongsTo(CertificationScheme::class, 'scheme_id'); }

    public function scopeActive($query) { return $query->where('status', 'active'); }

    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->isExpired();
    }
}

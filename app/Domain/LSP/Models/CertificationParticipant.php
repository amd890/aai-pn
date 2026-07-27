<?php

namespace App\Domain\LSP\Models;

use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use App\Support\Enums\CertificationParticipantStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CertificationParticipant extends BaseModel
{
    protected $fillable = [
        'batch_id', 'member_id', 'status',
        'assessment_date', 'result', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'assessment_date' => 'date',
            'status' => CertificationParticipantStatus::class,
        ];
    }

    public function batch(): BelongsTo { return $this->belongsTo(CertificationBatch::class, 'batch_id'); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function documents(): HasMany { return $this->hasMany(CertificationDocument::class, 'participant_id'); }
    public function certificate(): HasOne { return $this->hasOne(LspCertificate::class, 'participant_id'); }

    public function scopeCompetent($query) { return $query->where('status', CertificationParticipantStatus::Competent); }
}

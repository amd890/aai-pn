<?php

namespace App\Domain\LSP\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificationBatch extends BaseModel
{
    protected $fillable = [
        'scheme_id', 'tuk_id', 'batch_number', 'scheduled_date',
        'end_date', 'quota', 'assessor_id', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scheme(): BelongsTo { return $this->belongsTo(CertificationScheme::class, 'scheme_id'); }
    public function tuk(): BelongsTo { return $this->belongsTo(Tuk::class); }
    public function assessor(): BelongsTo { return $this->belongsTo(Assessor::class); }
    public function participants(): HasMany { return $this->hasMany(CertificationParticipant::class, 'batch_id'); }

    public function scopeOpen($query) { return $query->where('status', 'open'); }
    public function scopeUpcoming($query) { return $query->where('scheduled_date', '>', now()); }
}

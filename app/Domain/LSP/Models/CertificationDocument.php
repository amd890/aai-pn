<?php

namespace App\Domain\LSP\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationDocument extends BaseModel
{
    protected $fillable = [
        'participant_id', 'type', 'name', 'file_path',
        'file_size', 'verified', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'file_size' => 'integer',
        ];
    }

    public function participant(): BelongsTo { return $this->belongsTo(CertificationParticipant::class, 'participant_id'); }
}

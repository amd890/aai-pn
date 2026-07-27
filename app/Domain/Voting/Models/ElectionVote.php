<?php

namespace App\Domain\Voting\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionVote extends BaseModel
{
    protected $fillable = [
        'election_id', 'voter_hash', 'candidate_id',
        'otp_verified', 'voted_at', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'otp_verified' => 'boolean',
            'voted_at' => 'datetime',
        ];
    }

    public function election(): BelongsTo { return $this->belongsTo(Election::class); }
    public function candidate(): BelongsTo { return $this->belongsTo(ElectionCandidate::class, 'candidate_id'); }
}

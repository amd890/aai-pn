<?php

namespace App\Domain\Voting\Models;

use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionCandidate extends BaseModel
{
    protected $fillable = [
        'election_id', 'member_id', 'candidate_number',
        'vision_mission', 'profile_summary', 'photo',
        'status', 'vote_count',
    ];

    protected function casts(): array
    {
        return ['vote_count' => 'integer'];
    }

    public function election(): BelongsTo { return $this->belongsTo(Election::class); }
    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function votes(): HasMany { return $this->hasMany(ElectionVote::class, 'candidate_id'); }

    public function scopeActive($query) { return $query->where('status', 'active'); }
}

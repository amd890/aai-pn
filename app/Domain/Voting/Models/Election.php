<?php

namespace App\Domain\Voting\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use App\Support\Enums\ElectionLevel;
use App\Support\Enums\ElectionStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'level', 'type',
        'organization_unit_id', 'start_at', 'end_at', 'status',
        'max_vote', 'require_otp', 'featured_image',
        'eligible_criteria', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'level' => ElectionLevel::class,
            'status' => ElectionStatus::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'require_otp' => 'boolean',
            'eligible_criteria' => 'json',
        ];
    }

    public function organizationUnit(): BelongsTo { return $this->belongsTo(OrganizationUnit::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function candidates(): HasMany { return $this->hasMany(ElectionCandidate::class); }
    public function votes(): HasMany { return $this->hasMany(ElectionVote::class); }
    public function auditLogs(): HasMany { return $this->hasMany(ElectionAuditLog::class); }

    public function scopeOpen($query) { return $query->where('status', ElectionStatus::Open); }

    public function isOpen(): bool
    {
        return $this->status === ElectionStatus::Open
            && $this->start_at <= now()
            && $this->end_at >= now();
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }
}

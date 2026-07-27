<?php

namespace App\Domain\Membership\Models;

use App\Domain\Auth\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDocument extends BaseModel
{
    protected $fillable = [
        'member_id', 'type', 'name', 'file_path', 'file_size',
        'mime_type', 'verified', 'verified_by', 'verified_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'verified_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}

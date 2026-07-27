<?php

namespace App\Domain\Auth\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends BaseModel
{
    protected $fillable = [
        'user_id',
        'device_fingerprint',
        'device_name',
        'device_type',
        'browser',
        'platform',
        'trusted',
        'trusted_at',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'trusted' => 'boolean',
            'trusted_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTrusted($query)
    {
        return $query->where('trusted', true);
    }
}

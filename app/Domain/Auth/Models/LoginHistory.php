<?php

namespace App\Domain\Auth\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends BaseModel
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'location',
        'city',
        'country',
        'status',
        'login_at',
    ];

    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
        ];
    }

    protected array $searchable = ['ip_address', 'device', 'city', 'country'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}

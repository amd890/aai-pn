<?php

namespace App\Domain\Core\Models;

use App\Domain\Auth\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends BaseModel
{
    protected $fillable = [
        'module', 'action', 'auditable_type', 'auditable_id',
        'actor_id', 'before', 'after', 'ip_address',
        'user_agent', 'description',
    ];

    protected function casts(): array
    {
        return [
            'before' => 'json',
            'after' => 'json',
        ];
    }

    public function auditable(): MorphTo { return $this->morphTo(); }
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }

    public function scopeForModule($query, string $module) { return $query->where('module', $module); }
    public function scopeForAction($query, string $action) { return $query->where('action', $action); }
}

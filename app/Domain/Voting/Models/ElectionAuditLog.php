<?php

namespace App\Domain\Voting\Models;

use App\Domain\Auth\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionAuditLog extends BaseModel
{
    protected $fillable = [
        'election_id', 'action', 'description',
        'actor_id', 'ip_address', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'json'];
    }

    public function election(): BelongsTo { return $this->belongsTo(Election::class); }
    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }
}

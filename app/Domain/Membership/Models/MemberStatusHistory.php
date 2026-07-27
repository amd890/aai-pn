<?php

namespace App\Domain\Membership\Models;

use App\Domain\Auth\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberStatusHistory extends BaseModel
{
    protected $fillable = [
        'member_id', 'from_status', 'to_status',
        'reason', 'changed_by', 'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Domain\Organization\Models;

use App\Models\BaseModel;
use App\Domain\Membership\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationMember extends BaseModel
{
    protected $fillable = [
        'organization_period_id',
        'member_id',
        'position',
        'position_category',
        'sort_order',
        'status',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(OrganizationPeriod::class, 'organization_period_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

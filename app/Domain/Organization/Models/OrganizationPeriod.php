<?php

namespace App\Domain\Organization\Models;

use App\Models\BaseModel;
use App\Domain\Membership\Models\Member;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationPeriod extends BaseModel
{
    protected $fillable = [
        'organization_unit_id',
        'period_name',
        'start_year',
        'end_year',
        'sk_number',
        'sk_document_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_year' => 'integer',
            'end_year' => 'integer',
        ];
    }

    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function organizationMembers(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'organization_members')
            ->withPivot('position', 'position_category', 'sort_order', 'status')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

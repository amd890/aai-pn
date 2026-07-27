<?php

namespace App\Domain\Organization\Models;

use App\Models\BaseModel;
use App\Support\Enums\OrganizationUnitType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationUnit extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'code',
        'region_id',
        'parent_id',
        'address',
        'phone',
        'email',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrganizationUnitType::class,
        ];
    }

    protected array $searchable = ['name', 'code'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrganizationUnit::class, 'parent_id');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(OrganizationPeriod::class);
    }

    public function activePeriod()
    {
        return $this->hasOne(OrganizationPeriod::class)->where('status', 'active')->latestOfMany();
    }

    public function elections(): HasMany
    {
        return $this->hasMany(\App\Domain\Voting\Models\Election::class);
    }

    public function scopePusat($query)
    {
        return $query->where('type', OrganizationUnitType::Pusat);
    }

    public function scopeWilayah($query)
    {
        return $query->where('type', OrganizationUnitType::Wilayah);
    }

    public function scopeCabang($query)
    {
        return $query->where('type', OrganizationUnitType::Cabang);
    }
}

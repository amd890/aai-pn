<?php

namespace App\Domain\Organization\Models;

use App\Models\BaseModel;
use App\Support\Enums\RegionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'parent_id',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => RegionType::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_active' => 'boolean',
        ];
    }

    protected array $searchable = ['name', 'code'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function organizationUnits(): HasMany
    {
        return $this->hasMany(OrganizationUnit::class);
    }

    public function scopeProvinsi($query)
    {
        return $query->where('type', RegionType::Provinsi);
    }

    public function scopeKabupaten($query)
    {
        return $query->where('type', RegionType::Kabupaten);
    }

    public function members(): HasMany
    {
        return $this->hasMany(\App\Domain\Membership\Models\Member::class);
    }
}

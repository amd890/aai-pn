<?php

namespace App\Domain\Organization\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends BaseModel
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected array $searchable = ['name', 'type', 'city'];

    public function members(): HasMany
    {
        return $this->hasMany(\App\Domain\Membership\Models\Member::class);
    }
}

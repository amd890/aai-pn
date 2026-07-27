<?php

namespace App\Domain\LSP\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tuk extends BaseModel
{
    protected $fillable = [
        'name', 'code', 'type', 'address', 'city',
        'province', 'capacity', 'contact_person', 'phone', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected array $searchable = ['name', 'code', 'city'];

    public function batches(): HasMany { return $this->hasMany(CertificationBatch::class, 'tuk_id'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
}

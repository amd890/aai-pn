<?php

namespace App\Domain\LSP\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificationScheme extends BaseModel
{
    protected $fillable = [
        'name', 'code', 'level', 'description',
        'requirements', 'competency_units', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'json',
            'competency_units' => 'json',
            'is_active' => 'boolean',
        ];
    }

    protected array $searchable = ['name', 'code'];

    public function batches(): HasMany { return $this->hasMany(CertificationBatch::class, 'scheme_id'); }
    public function certificates(): HasMany { return $this->hasMany(LspCertificate::class, 'scheme_id'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
}

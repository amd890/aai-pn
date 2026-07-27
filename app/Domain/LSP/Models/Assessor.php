<?php

namespace App\Domain\LSP\Models;

use App\Domain\Membership\Models\Member;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessor extends BaseModel
{
    protected $fillable = [
        'member_id', 'license_number', 'license_expired_at',
        'specialization', 'status',
    ];

    protected function casts(): array
    {
        return ['license_expired_at' => 'date'];
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function batches(): HasMany { return $this->hasMany(CertificationBatch::class); }

    public function scopeActive($query) { return $query->where('status', 'active'); }

    public function isLicenseValid(): bool
    {
        return $this->license_expired_at && $this->license_expired_at->isFuture();
    }
}

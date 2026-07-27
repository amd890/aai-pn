<?php

namespace App\Domain\Membership\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Finance\Models\Due;
use App\Domain\Finance\Models\Payment;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\OrganizationMember;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Domain\Organization\Models\Region;
use App\Models\BaseModel;
use App\Support\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends BaseModel
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'member_number',
        'nik',
        'nip',
        'name',
        'photo',
        'gender',
        'birth_date',
        'birth_place',
        'institution_id',
        'position',
        'jenjang_arsiparis',
        'golongan',
        'education',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'status',
        'region_id',
        'organization_unit_id',
        'registered_at',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'registered_at' => 'datetime',
            'approved_at' => 'datetime',
            'status' => MemberStatus::class,
        ];
    }

    protected array $searchable = ['member_number', 'name', 'nik', 'nip', 'phone', 'city'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'member_number', 'status', 'position', 'jenjang_arsiparis'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function card(): HasOne
    {
        return $this->hasOne(MemberCard::class)->latestOfMany();
    }

    public function cards(): HasMany
    {
        return $this->hasMany(MemberCard::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(MemberCertificate::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(MemberStatusHistory::class);
    }

    public function dues(): HasMany
    {
        return $this->hasMany(Due::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', MemberStatus::Pending);
    }

    public function scopeActive($query)
    {
        return $query->where('status', MemberStatus::Active);
    }

    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('organization_unit_id', $unitId);
    }

    // ── Helpers ─────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === MemberStatus::Active;
    }

    public function isPending(): bool
    {
        return $this->status === MemberStatus::Pending;
    }
}

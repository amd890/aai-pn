<?php

namespace App\Domain\Auth\Models;

use App\Domain\Membership\Models\Member;
use App\Support\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;
    use LogsActivity;

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'avatar',
        'status',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'google_id',
        'google_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'google_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    /**
     * Searchable columns for the search scope.
     */
    protected array $searchable = ['name', 'email', 'username', 'phone'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'username', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ──────────────────────────────────────

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::Active);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")
              ->orWhere('email', 'LIKE', "%{$keyword}%")
              ->orWhere('username', 'LIKE', "%{$keyword}%");
        });
    }

    // ── Helpers ─────────────────────────────────────────────

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }
}

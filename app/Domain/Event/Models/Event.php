<?php

namespace App\Domain\Event\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\OrganizationUnit;
use App\Models\BaseModel;
use App\Support\Enums\EventFormat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends BaseModel implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'type', 'format',
        'location', 'map_url', 'zoom_link', 'zoom_id', 'zoom_password',
        'quota', 'price', 'is_free', 'featured_image',
        'registration_start', 'registration_end', 'event_start', 'event_end',
        'status', 'organization_unit_id', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'format' => EventFormat::class,
            'price' => 'decimal:2',
            'is_free' => 'boolean',
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'event_start' => 'datetime',
            'event_end' => 'datetime',
        ];
    }

    protected array $searchable = ['title', 'description', 'location'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('documents');
    }

    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_start', '>', now());
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now());
    }

    public function isRegistrationOpen(): bool
    {
        return $this->registration_start <= now()
            && $this->registration_end >= now()
            && ! $this->isQuotaFull();
    }

    public function isQuotaFull(): bool
    {
        if (is_null($this->quota)) return false;
        return $this->registrations()->where('status', 'confirmed')->count() >= $this->quota;
    }

    public function confirmedCount(): int
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }
}

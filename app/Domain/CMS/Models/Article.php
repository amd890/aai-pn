<?php

namespace App\Domain\CMS\Models;

use App\Domain\Auth\Models\User;
use App\Models\BaseModel;
use App\Support\Enums\ArticleStatus;
use App\Support\Enums\ArticleType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends BaseModel implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'seo_title', 'seo_description', 'seo_keywords',
        'og_image', 'type', 'status', 'published_at', 'author_id',
        'view_count', 'is_featured', 'is_pinned',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'type' => ArticleType::class,
            'status' => ArticleStatus::class,
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'view_count' => 'integer',
        ];
    }

    protected array $searchable = ['title', 'excerpt', 'content'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
        $this->addMediaCollection('content_images');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::Published)
            ->where('published_at', '<=', now());
    }

    public function scopeNews($query)
    {
        return $query->where('type', ArticleType::News);
    }

    public function scopeArticles($query)
    {
        return $query->where('type', ArticleType::Article);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}

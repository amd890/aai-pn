<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends BaseModel
{
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ArticleCategory::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

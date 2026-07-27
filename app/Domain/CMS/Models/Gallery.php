<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends BaseModel
{
    protected $fillable = ['title', 'slug', 'description', 'cover_image', 'status', 'sort_order'];

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}

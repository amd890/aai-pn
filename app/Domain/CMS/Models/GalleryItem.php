<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends BaseModel
{
    protected $fillable = [
        'gallery_id', 'type', 'title', 'description',
        'file_path', 'video_url', 'thumbnail', 'sort_order',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }
}

<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;

class Agenda extends BaseModel
{
    protected $fillable = [
        'title', 'slug', 'description', 'content', 'location',
        'map_url', 'start_date', 'end_date', 'featured_image',
        'status', 'is_featured', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    protected array $searchable = ['title', 'description', 'location'];

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', now()));
    }
}

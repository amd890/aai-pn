<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;

class Banner extends BaseModel
{
    protected $fillable = [
        'title', 'image_path', 'url', 'description',
        'sort_order', 'is_active', 'start_date', 'end_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', now()))
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', now()));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}

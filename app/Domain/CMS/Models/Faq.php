<?php

namespace App\Domain\CMS\Models;

use App\Models\BaseModel;

class Faq extends BaseModel
{
    protected $fillable = ['question', 'answer', 'category', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected array $searchable = ['question', 'answer'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}

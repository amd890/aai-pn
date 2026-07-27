<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseModel extends Model
{
    use HasFactory;

    /**
     * Default number of items per page for pagination.
     */
    protected $perPage = 15;

    /**
     * Scope: filter by active status.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: search by keyword in searchable columns.
     * Override $searchable in child models.
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        $searchable = $this->searchable ?? [];

        return $query->where(function ($q) use ($keyword, $searchable) {
            foreach ($searchable as $column) {
                $q->orWhere($column, 'LIKE', "%{$keyword}%");
            }
        });
    }

    /**
     * Scope: filter by date range.
     */
    public function scopeDateBetween($query, string $column, ?string $from, ?string $to)
    {
        if ($from) {
            $query->where($column, '>=', $from);
        }
        if ($to) {
            $query->where($column, '<=', $to);
        }

        return $query;
    }
}

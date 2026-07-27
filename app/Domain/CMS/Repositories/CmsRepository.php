<?php

namespace App\Domain\CMS\Repositories;

use App\Domain\CMS\Models\Agenda;
use App\Domain\CMS\Models\Article;
use App\Support\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CmsRepository extends EloquentRepository
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function getPublishedArticles(int $perPage = 9, array $filters = []): LengthAwarePaginator
    {
        $query = Article::with(['category', 'author'])
            ->published()
            ->latest('published_at');

        if (!empty($filters['category'])) {
            $query->whereHas('category', fn($q) => $q->where('slug', $filters['category']));
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Article
    {
        return Article::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->first();
    }

    public function getUpcomingAgendas(int $limit = 5)
    {
        return Agenda::upcoming()
            ->orderBy('start_date', 'asc')
            ->take($limit)
            ->get();
    }
}

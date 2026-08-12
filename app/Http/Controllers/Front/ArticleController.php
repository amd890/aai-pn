<?php

namespace App\Http\Controllers\Front;

use App\Domain\CMS\Repositories\CmsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(protected CmsRepository $cmsRepository)
    {
    }

    public function index(Request $request): View
    {
        $filters = [
            'category' => $request->get('category'),
            'search' => $request->get('search'),
        ];

        // Cache daftar artikel berdasarkan filter+page (5 menit)
        // Tidak cache jika ada pencarian (search selalu fresh)
        $cacheKey = 'front:articles:' . md5(json_encode($filters) . ':p' . $request->get('page', 1));
        
        if (!empty($filters['search'])) {
            $articles = $this->cmsRepository->getPublishedArticles(9, $filters);
        } else {
            $articles = Cache::remember($cacheKey, 300, fn () =>
                $this->cmsRepository->getPublishedArticles(9, $filters)
            );
        }

        return view('front.articles.index', compact('articles', 'filters'));
    }

    public function show(string $slug): View
    {
        // Cache detail artikel per slug (10 menit)
        $article = Cache::remember("front:article:{$slug}", 600, fn () =>
            $this->cmsRepository->findPublishedBySlug($slug)
        );

        if (! $article) {
            abort(404, 'Artikel tidak ditemukan atau belum dipublikasikan.');
        }

        // Increment view count langsung ke DB (tidak perlu di-cache)
        $article->increment('view_count');

        // Cache related articles (5 menit)
        $relatedArticles = Cache::remember("front:article:{$slug}:related", 300, fn () =>
            $this->cmsRepository->getPublishedArticles(3, ['category' => $article->category?->slug])
        );

        return view('front.articles.show', compact('article', 'relatedArticles'));
    }
}

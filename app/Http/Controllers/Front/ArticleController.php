<?php

namespace App\Http\Controllers\Front;

use App\Domain\CMS\Repositories\CmsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        $articles = $this->cmsRepository->getPublishedArticles(9, $filters);

        return view('front.articles.index', compact('articles', 'filters'));
    }

    public function show(string $slug): View
    {
        $article = $this->cmsRepository->findPublishedBySlug($slug);

        if (! $article) {
            abort(404, 'Artikel tidak ditemukan atau belum dipublikasikan.');
        }

        // Increment reading view count atomically
        $article->increment('view_count');

        $relatedArticles = $this->cmsRepository->getPublishedArticles(3, ['category' => $article->category?->slug]);

        return view('front.articles.show', compact('article', 'relatedArticles'));
    }
}

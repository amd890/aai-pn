<?php

namespace App\Http\Controllers\Front;

use App\Domain\CMS\Models\Banner;
use App\Domain\CMS\Repositories\CmsRepository;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected CmsRepository $cmsRepository)
    {
    }

    public function index(Request $request): View
    {
        // Cache statistik keanggotaan selama 30 menit
        $stats = Cache::remember('front:home-stats', 1800, function () {
            return [
                'total_members' => Member::active()->count(),
                'total_regions' => Region::count(),
                'total_institutions' => Institution::count(),
                'total_ahli_utama' => Member::active()->where('jenjang_arsiparis', 'Ahli Utama')->count(),
                'total_ahli_madya' => Member::active()->where('jenjang_arsiparis', 'Ahli Madya')->count(),
                'total_ahli_muda' => Member::active()->where('jenjang_arsiparis', 'Ahli Muda')->count(),
                'total_terampil' => Member::active()->where('jenjang_arsiparis', 'Terampil')->count(),
            ];
        });

        // Cache berita terbaru selama 10 menit
        $latestNews = Cache::remember('front:home-news', 600, fn () =>
            $this->cmsRepository->getPublishedArticles(3)
        );

        // Cache agenda mendatang selama 15 menit
        $upcomingAgendas = Cache::remember('front:home-agendas', 900, fn () =>
            $this->cmsRepository->getUpcomingAgendas(4)
        );

        // Cache banner selama 1 jam
        $activeBanners = Cache::remember('front:home-banners', 3600, fn () =>
            Banner::where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->take(3)
                ->get()
        );

        return view('front.home', compact('stats', 'latestNews', 'upcomingAgendas', 'activeBanners'));
    }
}

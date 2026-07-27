<?php

namespace App\Http\Controllers\Front;

use App\Domain\CMS\Models\Banner;
use App\Domain\CMS\Repositories\CmsRepository;
use App\Domain\Membership\Models\Member;
use App\Domain\Organization\Models\Institution;
use App\Domain\Organization\Models\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected CmsRepository $cmsRepository)
    {
    }

    public function index(Request $request): View
    {
        $stats = [
            'total_members' => Member::active()->count(),
            'total_regions' => Region::count(),
            'total_institutions' => Institution::count(),
            'total_ahli_utama' => Member::active()->where('jenjang_arsiparis', 'Ahli Utama')->count(),
            'total_ahli_madya' => Member::active()->where('jenjang_arsiparis', 'Ahli Madya')->count(),
            'total_ahli_muda' => Member::active()->where('jenjang_arsiparis', 'Ahli Muda')->count(),
            'total_terampil' => Member::active()->where('jenjang_arsiparis', 'Terampil')->count(),
        ];

        $latestNews = $this->cmsRepository->getPublishedArticles(3);
        $upcomingAgendas = $this->cmsRepository->getUpcomingAgendas(4);
        
        $activeBanners = Banner::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        return view('front.home', compact('stats', 'latestNews', 'upcomingAgendas', 'activeBanners'));
    }
}

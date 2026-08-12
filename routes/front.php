<?php

use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\SitemapController;
use App\Http\Controllers\Front\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front-end (Public) Routes
|--------------------------------------------------------------------------
*/

// SEO & AI Crawlers
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/llms.txt', [SitemapController::class, 'llmsTxt'])->name('llms-txt');

Route::name('front.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'about')->name('about');
    Route::get('/about/aai-pn', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'about-aai-pn')->name('about.aai-pn');
    Route::get('/organization', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'organization')->name('organization');
    Route::get('/annual-report', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'annual-report')->name('annual-report');
    Route::get('/careers', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'careers')->name('careers');
    Route::get('/regulations', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'regulations')->name('regulations');


    // News & Articles
    Route::get('/news', [ArticleController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [ArticleController::class, 'show'])->name('news.show');
    Route::get('/article', [ArticleController::class, 'index'])->name('article.index');
    Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('article.show');

    // Agenda, Memory & Publications
    Route::get('/agenda', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'agenda')->name('agenda.index');
    Route::get('/agenda/{slug}', fn() => '')->name('agenda.show');
    Route::get('/memory-today', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'memory-today')->name('memory-today');
    Route::get('/publications', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'publications')->name('publications');
    Route::get('/gallery', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'gallery')->name('gallery.index');

    // Static Pages
    Route::get('/page/{slug}', \App\Livewire\Front\DynamicPage::class)->name('page.show');
    Route::get('/faq', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'faq')->name('faq');
    Route::get('/contact', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'contact')->name('contact');
    Route::get('/downloads', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'downloads')->name('downloads');

    // Membership Public & Verification
    Route::get('/membership', fn() => '')->name('membership.info');
    Route::get('/membership/register', \App\Livewire\Auth\Register::class)->name('membership.register');
    Route::get('/membership/verify', [VerificationController::class, 'membership'])->name('membership.verify');

    // Digital Verifications
    Route::get('/certification', fn() => '')->name('certification.info');
    Route::get('/certification/verify', [VerificationController::class, 'certification'])->name('certification.verify');
    Route::get('/card/verify', [VerificationController::class, 'card'])->name('card.verify');

    // Legal
    Route::get('/privacy-policy', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'privacy-policy')->name('privacy-policy');
    Route::get('/terms', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'terms')->name('terms');
    Route::get('/cookies', \App\Livewire\Front\DynamicPage::class)->defaults('slug', 'cookies')->name('cookies');
});

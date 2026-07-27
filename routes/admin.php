<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Phase 3 Enterprise Architecture)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Executive KPI Dashboard
    Route::get('/dashboard', \App\Livewire\Admin\ExecutiveDashboard::class)->name('dashboard');

    // ── Members & Verification Queue ────────────────────────
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Membership\VerificationQueue::class)->name('index');
        Route::get('/verification-queue', \App\Livewire\Admin\Membership\VerificationQueue::class)->name('verification-queue');
        Route::get('/create', fn() => '')->name('create');
        Route::get('/{id}', fn() => '')->name('show');
        Route::get('/{id}/edit', fn() => '')->name('edit');
        Route::get('/{id}/card', fn() => '')->name('card');
        Route::get('/{id}/certificate', fn() => '')->name('certificate');
        Route::get('/import', fn() => '')->name('import');
        Route::get('/export', fn() => '')->name('export');
    });

    // ── Organization ─────────────────────────────────────────
    Route::prefix('organization')->name('organization.')->group(function () {
        Route::get('/units', \App\Livewire\Admin\Organization\UnitManager::class)->name('units.index');
        Route::get('/periods', fn() => '')->name('periods.index');
        Route::get('/regions', fn() => '')->name('regions.index');
    });

    // ── Finance & Payment Verification ───────────────────────
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/dues', fn() => '')->name('dues.index');
        Route::get('/payments', \App\Livewire\Admin\Finance\PaymentVerification::class)->name('payments.index');
        Route::get('/invoices', fn() => '')->name('invoices.index');
    });

    // ── CMS ──────────────────────────────────────────────────
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/articles', \App\Livewire\Admin\Content\ArticleManager::class)->name('articles.index');
        Route::get('/news', \App\Livewire\Admin\Content\ArticleManager::class)->name('news.index');
        Route::get('/pages', \App\Livewire\Admin\Content\PageManager::class)->name('pages.index');
        Route::get('/menus', \App\Livewire\Admin\Content\MenuManager::class)->name('menus.index');
        Route::get('/gallery', fn() => '')->name('gallery.index');
        Route::get('/agenda', fn() => '')->name('agenda.index');
        Route::get('/banners', fn() => '')->name('banners.index');
        Route::get('/faq', fn() => '')->name('faq.index');
    });

    // ── Events ───────────────────────────────────────────────
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Events\EventManager::class)->name('index');
        Route::get('/{id}/registrations', fn() => '')->name('registrations');
        Route::get('/{id}/attendance', fn() => '')->name('attendance');
    });

    // ── LSP ──────────────────────────────────────────────────
    Route::prefix('lsp')->name('lsp.')->group(function () {
        Route::get('/schemes', fn() => '')->name('schemes.index');
        Route::get('/assessors', fn() => '')->name('assessors.index');
        Route::get('/tuk', fn() => '')->name('tuk.index');
        Route::get('/batches', \App\Livewire\Admin\Lsp\BatchManager::class)->name('batches.index');
    });

    // ── Voting ───────────────────────────────────────────────
    Route::prefix('voting')->name('voting.')->group(function () {
        Route::get('/elections', \App\Livewire\Admin\Voting\ElectionManager::class)->name('elections.index');
        Route::get('/elections/{id}/results', fn() => '')->name('elections.results');
    });

    // ── Correspondence (Tata Naskah Dinas) ───────────────────
    Route::prefix('correspondence')->name('correspondence.')->group(function () {
        Route::get('/in', fn() => '')->name('in.index');
        Route::get('/out', \App\Livewire\Admin\Correspondence\LetterManager::class)->name('out.index');
    });

    // ── System ───────────────────────────────────────────────
    Route::get('/notifications', fn() => '')->name('notifications.index');
    Route::get('/reports', fn() => '')->name('reports.index');
    Route::get('/users', fn() => '')->name('users.index');
    Route::get('/roles', fn() => '')->name('roles.index');
    Route::get('/permissions', fn() => '')->name('permissions.index');

    // ── Logs ─────────────────────────────────────────────────
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/activity', fn() => '')->name('activity');
        Route::get('/audit', \App\Livewire\Admin\System\AuditLogViewer::class)->name('audit');
    });

    // ── Settings ─────────────────────────────────────────────
    Route::get('/seo', fn() => '')->name('seo');
    Route::get('/media', fn() => '')->name('media');
    Route::get('/backup', fn() => '')->name('backup');
    Route::get('/settings', fn() => '')->name('settings');
});

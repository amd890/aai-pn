<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
| Routes here are prefixed with /api and use Sanctum for authentication.
| API implementations will be added in Phase 3-5.
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── Public API ───────────────────────────────────────────
    Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()]));

    // Verification endpoints (public)
    Route::prefix('verify')->name('verify.')->group(function () {
        Route::get('/member/{number}', fn() => '')->name('member');
        Route::get('/certificate/{number}', fn() => '')->name('certificate');
        Route::get('/card/{number}', fn() => '')->name('card');
    });

    // Public content
    Route::get('/news', fn() => '')->name('news.index');
    Route::get('/articles', fn() => '')->name('articles.index');
    Route::get('/agenda', fn() => '')->name('agenda.index');
    Route::get('/events', fn() => '')->name('events.index');

    // ── Authenticated API ────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', fn(\Illuminate\Http\Request $request) => $request->user())->name('user');

        // Member profile
        Route::get('/profile', fn() => '')->name('profile');
        Route::put('/profile', fn() => '')->name('profile.update');

        // Member dues
        Route::get('/dues', fn() => '')->name('dues.index');

        // Event registration
        Route::post('/events/{id}/register', fn() => '')->name('events.register');

        // Voting
        Route::get('/elections', fn() => '')->name('elections.index');
        Route::post('/elections/{id}/vote', fn() => '')->name('elections.vote');
    });
});

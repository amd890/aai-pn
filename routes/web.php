<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Phase 3 Integration)
|--------------------------------------------------------------------------
*/

// Front-end (public) routes
require __DIR__.'/front.php';

// Admin panel routes
require __DIR__.'/admin.php';

// Authentication Routes (Livewire Replicas)
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('front.home');
})->name('logout');

// Member Self-Service Portal Routes (Enterprise Portal)
Route::prefix('portal')->name('portal.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Portal\MemberDashboard::class)->name('dashboard');
    Route::get('/kta', fn() => redirect()->route('portal.dashboard'))->name('kta');
    Route::get('/invoices', fn() => redirect()->route('portal.dashboard'))->name('invoices');
    
    // E-Voting & LSP Certification & Event
    Route::get('/voting', \App\Livewire\Portal\ElectionVoting::class)->name('voting');
    Route::get('/certification', \App\Livewire\Portal\LspCertification::class)->name('certification');
    Route::get('/events', \App\Livewire\Portal\EventRegistrationLivewire::class)->name('events');
});

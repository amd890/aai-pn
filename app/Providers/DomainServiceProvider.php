<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repository bindings will be added here as repositories are implemented
        // Example:
        // $this->app->bind(
        //     \App\Domain\Membership\Repositories\Contracts\MemberRepositoryInterface::class,
        //     \App\Domain\Membership\Repositories\Eloquent\MemberRepository::class
        // );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Super admin bypasses all permission checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Register Observers
        // \App\Domain\Membership\Models\Member::observe(\App\Domain\Membership\Observers\MemberObserver::class);

        // Register Event listeners via EventServiceProvider or here
    }
}

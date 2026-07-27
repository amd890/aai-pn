<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\DomainServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            if (auth()->check()) {
                if (auth()->user()->hasAnyRole(['super-admin', 'administrator', 'sekretariat-nasional', 'bendahara-nasional', 'pengurus-wilayah', 'verifier-anggota', 'lsp-admin'])) {
                    return route('admin.dashboard');
                }
                return route('portal.dashboard');
            }
            return route('front.home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

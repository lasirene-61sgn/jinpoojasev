<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Where to redirect users if they are ALREADY logged in and try to visit a guest page (like /superadmin/login)
        $middleware->redirectUsersTo(function (Request $request) {
            if (auth()->guard('superadmin')->check()) {
                return route('superadmin.dashboard');
            }
            
            return '/dashboard'; // Default for regular admins/users
        });

        // 2. Where to redirect users if they are NOT logged in and try to visit a protected page
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('superadmin/*') || $request->is('superadmin')) {
                return route('superadmin.login');
            }
            
            return route('login'); // Default for regular admins/users
        });

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias route middleware di sini.
        // Pastikan class-class middleware ini ada di project kamu.
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Railway uses dynamic public IPs — trust all proxies for HTTPS detection
        $middleware->trustProxies(at: '*');

        // CacheResponse as GLOBAL middleware: on cache hits, it returns immediately
        // BEFORE StartSession, SecurityHeaders, etc. — saving ~1700ms per page load.
        // DoNotCacheResponse can still override per-route to prevent caching.
        $middleware->append(\Spatie\ResponseCache\Middlewares\CacheResponse::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\BladeFlowMiddleware::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();

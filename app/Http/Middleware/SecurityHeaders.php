<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        // Content Security Policy - allow Vite dev server in local environment
        $viteHttp = app()->isLocal() ? ' http://localhost:5173 http://127.0.0.1:5173' : '';
        $viteWs = app()->isLocal() ? ' ws://localhost:5173 ws://127.0.0.1:5173' : '';
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'{$viteHttp}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com{$viteHttp}",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com{$viteHttp}",
            "img-src 'self' data:",
            "connect-src 'self'{$viteHttp}{$viteWs}",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'",
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}

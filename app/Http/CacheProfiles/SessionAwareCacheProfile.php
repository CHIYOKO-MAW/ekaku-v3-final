<?php

namespace App\Http\CacheProfiles;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;
use Symfony\Component\HttpFoundation\Response;

class SessionAwareCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Use the session cookie value as the cache key suffix.
     *
     * This ensures each authenticated user gets their own cached response,
     * so it's safe to return cached responses before StartSession/role middleware
     * (global CacheResponse middleware).
     *
     * Without this, all users share the same cache key (empty suffix),
     * causing a security bug where an admin's cached 200 response is served
     * to a regular user who should get a 403.
     */
    public function useCacheNameSuffix(Request $request): string
    {
        $sessionCookie = $request->cookies->get((string) config('session.cookie', 'laravel_session'));

        return $sessionCookie ?: '';
    }

    /**
     * Only cache successful 2xx responses, NOT redirects (3xx).
     *
     * Redirects should not be cached because:
     * 1. CacheResponse runs as global middleware before session/auth
     * 2. Cached redirects bypass downstream middleware (auth, role checks)
     * 3. Redirect destinations may change based on user role
     */
    public function hasCacheableResponseCode(Response $response): bool
    {
        return $response->isSuccessful();
    }
}

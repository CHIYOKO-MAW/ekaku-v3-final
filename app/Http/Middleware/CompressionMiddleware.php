<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isRedirection()) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');
        if (str_starts_with($contentType, 'application/pdf')
            || str_starts_with($contentType, 'image/')
            || str_starts_with($contentType, 'video/')
            || str_starts_with($contentType, 'audio/')
            || str_starts_with($contentType, 'application/zip')
            || str_starts_with($contentType, 'application/octet-stream')
        ) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return $response;
        }

        $acceptEncoding = $request->header('Accept-Encoding', '');

        if (str_contains($acceptEncoding, 'br') && function_exists('brotli_compress')) {
            $compressed = brotli_compress($content, 6);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'br');
                $response->headers->set('Vary', 'Accept-Encoding', true);
            }
        } elseif (str_contains($acceptEncoding, 'gzip') && function_exists('gzencode')) {
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Vary', 'Accept-Encoding', true);
            }
        }

        return $response;
    }
}

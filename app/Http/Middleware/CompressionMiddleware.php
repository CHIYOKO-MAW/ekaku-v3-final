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

        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');
        if (!str_starts_with($contentType, 'text/') &&
            !str_contains($contentType, 'javascript') &&
            !str_contains($contentType, 'json') &&
            !str_contains($contentType, 'xml') &&
            !str_contains($contentType, 'css') &&
            !str_contains($contentType, 'svg')) {
            return $response;
        }

        $acceptEncoding = $request->header('Accept-Encoding', '');
        $content = $response->getContent();

        if (strlen($content) < 1024) {
            return $response;
        }

        if (str_contains($acceptEncoding, 'br') && function_exists('brotli_compress')) {
            $compressed = brotli_compress($content);
            if ($compressed !== false && strlen($compressed) < strlen($content)) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'br');
                $response->headers->set('Content-Length', strlen($compressed));
                $response->headers->set('Vary', 'Accept-Encoding');
                return $response;
            }
        }

        if (str_contains($acceptEncoding, 'gzip') && function_exists('gzencode')) {
            $compressed = gzencode($content, 6);
            if ($compressed !== false && strlen($compressed) < strlen($content)) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Content-Length', strlen($compressed));
                $response->headers->set('Vary', 'Accept-Encoding');
                return $response;
            }
        }

        return $response;
    }
}
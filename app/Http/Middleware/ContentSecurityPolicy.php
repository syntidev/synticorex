<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // En desarrollo, Vite corre en localhost:5173 — no aplicar CSP
        if (app()->environment('local')) {
            return $response;
        }

        // Content Security Policy header
        // Allows Google Maps iframe and other safe external resources
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' unpkg.com cdn.jsdelivr.net cdn.flyonui.com icon-sets.iconify.design code.iconify.design",
            "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdn.flyonui.com fonts.googleapis.com",
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: fonts.gstatic.com cdn.jsdelivr.net cdn.flyonui.com",
            "connect-src 'self' https:",
            "frame-src 'self' https://www.google.com/maps/",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}

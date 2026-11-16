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

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self "https://secure.ccavenue.com")');

        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        if (! $response->headers->has('Content-Security-Policy') && ! $request->is('admin/*')) {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; "
                ."img-src 'self' data: https:; "
                ."style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
                ."font-src 'self' data: https://fonts.gstatic.com; "
                ."script-src 'self' 'unsafe-inline' https://www.google.com https://www.gstatic.com; "
                ."frame-src https://www.google.com; "
                ."connect-src 'self' https:; "
                ."object-src 'none'; "
                ."base-uri 'self'; "
                ."form-action 'self' https://secure.ccavenue.com; "
                ."frame-ancestors 'self'"
            );
        }

        return $response;
    }
}

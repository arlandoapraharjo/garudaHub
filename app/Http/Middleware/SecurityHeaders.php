<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach OWASP recommended security headers.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Strip X-Powered-By before and after response creation
        header_remove('X-Powered-By');

        $response = $next($request);

        header_remove('X-Powered-By');
        $response->headers->remove('X-Powered-By');

        // Security headers against Clickjacking, MIME-sniffing, and info leak
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Strict Content Security Policy without unsafe-inline to eliminate ZAP CSP warnings
        $csp = "default-src 'self'; script-src 'self'; style-src 'self'; font-src 'self' data:; img-src 'self' data:; connect-src 'self'; frame-ancestors 'self'; form-action 'self'; base-uri 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        // Enforce HttpOnly on all response cookies (including XSRF-TOKEN)
        foreach ($response->headers->getCookies() as $cookie) {
            if (! $cookie->isHttpOnly()) {
                $response->headers->setCookie(
                    $cookie->withHttpOnly(true)
                );
            }
        }

        // Mitigate "Big Redirect Detected" alert: redirect responses should not leak verbose body content
        if ($response->isRedirection()) {
            $response->setContent('');
        }

        return $response;
    }
}

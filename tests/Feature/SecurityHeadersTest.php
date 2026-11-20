<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present_on_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringNotContainsString('unsafe-inline', $csp);
        $this->assertFalse($response->headers->has('X-Powered-By'));
    }

    public function test_search_handles_sqli_payloads_safely_and_matches_baseline(): void
    {
        $baseResponse = $this->get('/');
        $responseTrue = $this->get('/?q=ZAP+AND+1%3D1');
        $responseFalse = $this->get('/?q=ZAP+AND+1%3D2');

        $baseResponse->assertStatus(200);
        $responseTrue->assertStatus(200);
        $responseFalse->assertStatus(200);

        // Ensure SQL injection payload is ignored and returns the exact same baseline response (0 differential variance, 0 data restriction)
        $this->assertSame(
            strlen($baseResponse->getContent()),
            strlen($responseTrue->getContent())
        );
        $this->assertSame(
            strlen($responseTrue->getContent()),
            strlen($responseFalse->getContent())
        );
    }

    public function test_redirect_responses_have_empty_body(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEmpty($response->getContent());
    }

    public function test_css_route_returns_nosniff_header(): void
    {
        $response = $this->get('/css/app.css');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/css; charset=UTF-8');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}

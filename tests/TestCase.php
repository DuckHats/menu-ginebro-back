<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

/**
 * Helper methods for tests to simulate SPA cookie authentication
 */

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Perform SPA cookie-based login for a user and return cookies and response.
     * Accepts either a User instance or an email string and password.
     */
    public function loginViaSession($userOrEmail, $password = 'password123')
    {
        $email = $userOrEmail instanceof User ? $userOrEmail->email : $userOrEmail;

        // Step 1: get CSRF cookie
        $csrfResp = $this->get('/sanctum/csrf-cookie');
        $csrfResp->assertStatus(204);

        $xsrfCookie = null;
        $sessionCookie = null;
        foreach ($csrfResp->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'XSRF-TOKEN') {
                $xsrfCookie = $cookie->getValue();
            }

            if ($cookie->getName() === config('session.cookie')) {
                $sessionCookie = $cookie->getValue();
            }
        }

        $this->assertNotNull($xsrfCookie, 'XSRF cookie was not set by /sanctum/csrf-cookie');

        $headers = ['X-XSRF-TOKEN' => urldecode($xsrfCookie)];

        $loginResp = $this->withCookie('XSRF-TOKEN', $xsrfCookie)
            ->withCookie(config('session.cookie'), $sessionCookie)
            ->postJson(route('auth.login'), [
                'user' => $email,
                'password' => $password,
            ], $headers);

        foreach ($loginResp->headers->getCookies() as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                $sessionCookie = $cookie->getValue();
            }
        }

        return ['response' => $loginResp, 'xsrf' => $xsrfCookie, 'session_cookie' => $sessionCookie];
    }
}

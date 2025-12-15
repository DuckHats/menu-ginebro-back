<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthSessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function spa_login_flow_with_csrf_cookie_and_session_works()
    {
        $password = 'password123';
        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make($password),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $adminType->id,
        ]);

        // Step 1: get CSRF cookie
        $response = $this->get('/sanctum/csrf-cookie');
        $response->assertStatus(204);
        $response->assertCookie('XSRF-TOKEN');

        // Extract cookies from response
        $xsrfCookie = null;
        $sessionCookie = null;

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'XSRF-TOKEN') {
                $xsrfCookie = $cookie->getValue();
            }

            if ($cookie->getName() === config('session.cookie')) {
                $sessionCookie = $cookie->getValue();
            }
        }

        $this->assertNotNull($xsrfCookie, 'XSRF cookie was not set');

        // Step 2: POST login with X-XSRF-TOKEN header and cookies
        $headers = [
            'X-XSRF-TOKEN' => urldecode($xsrfCookie),
        ];

        $loginResponse = $this->withCookie('XSRF-TOKEN', $xsrfCookie)
            ->withCookie(config('session.cookie'), $sessionCookie)
            ->postJson('/api/v1/login', [
                'user' => 'test@example.com',
                'password' => $password,
            ], $headers);

        $loginResponse->assertStatus(200);
        $this->assertAuthenticatedAs($user);

        // Step 3: access protected endpoint using the session cookie
        $meResponse = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson('/api/v1/users/me');

        $meResponse->assertStatus(200);
        $meResponse->assertJsonFragment(['email' => 'test@example.com']);
    }
}

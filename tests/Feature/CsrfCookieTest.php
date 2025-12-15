<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsrfCookieTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function csrf_cookie_is_set()
    {
        $response = $this->get('/sanctum/csrf-cookie');

        $response->assertStatus(204);
        $response->assertCookie('XSRF-TOKEN');
    }
}

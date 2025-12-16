<?php

namespace Tests\Feature;

use App\Models\EmailVerification;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// Using cookie-based session flow for SPA auth tests
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $cookUser;

    protected $adminUser;

    protected $token;

    protected $adminToken;

    protected $cookToken;

    protected $userType;

    protected $adminType;

    protected $cookType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);
        $this->userType = UserType::factory()->create(['id' => 2, 'name' => 'usuari']);
        $this->cookType = UserType::factory()->create(['id' => 3, 'name' => 'cuina']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->userType->id,
        ]);

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->adminType->id,
        ]);

        $this->cookUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->cookType->id,
        ]);
    }

    /** @test */
    public function it_can_register_a_user()
    {
        $userData = [
            'name' => 'TestUser',
            'last_name' => 'TestLastName',
            'email' => 'test_user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type_id' => $this->userType->id,
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_register_a_admin()
    {
        $userData = [
            'name' => 'TestAdminUser',
            'last_name' => 'TestAdminLastName',
            'email' => 'test_admin_user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type_id' => $this->adminType->id,
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_register_a_cooker()
    {
        $userData = [
            'name' => 'TestCookUser',
            'last_name' => 'TestCookLastName',
            'email' => 'test_cook_user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type_id' => $this->cookType->id,
        ];

        $response = $this->postJson(route('auth.register'), $userData);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_sends_verification_code_for_register()
    {
        $data = [
            'email' => 'newuser@example.com',
        ];

        $response = $this->postJson(route('auth.sendRegisterCode'), $data);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_complete_register_with_valid_code()
    {
        $email = 'newuser@example.com';
        $sendRegisterCodeData = [
            'email' => 'newuser@example.com',
        ];
        $response = $this->postJson(route('auth.sendRegisterCode'), $sendRegisterCodeData);
        $response->assertStatus(200);
        $code = EmailVerification::where('email', $email)->first()->verification_code;

        $response = $this->postJson(route('auth.completeRegister'), [
            'name' => 'New',
            'last_name' => 'User',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'verification_code' => $code,
            'user_type_id' => $this->userType->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    /** @test */
    public function it_can_login_a_user()
    {
        $result = $this->loginViaSession($this->user->email, 'password123');
        $response = $result['response'];
        $sessionCookie = $result['session_cookie'];

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->user);

        // Ensure protected endpoint is accessible using returned session cookie
        $meResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('users.me'));
        $meResp->assertStatus(200);
    }

    /** @test */
    public function it_can_login_a_admin()
    {
        $result = $this->loginViaSession($this->adminUser->email, 'password123');
        $response = $result['response'];
        $sessionCookie = $result['session_cookie'];

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->adminUser);

        $meResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('users.me'));
        $meResp->assertStatus(200);
    }

    /** @test */
    public function it_can_login_a_cooker()
    {
        $result = $this->loginViaSession($this->cookUser->email, 'password123');
        $response = $result['response'];
        $sessionCookie = $result['session_cookie'];

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($this->cookUser);

        $meResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('users.me'));
        $meResp->assertStatus(200);
    }

    /** @test */
    public function it_should_fail_if_login_with_invalid_credentials()
    {
        $loginData = [
            'user' => $this->user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        // Perform login with cookie/session flow
        $result = $this->loginViaSession($this->user->email, 'password123');
        $response = $result['response'];
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        // Logout using session cookie and CSRF header
        $logoutResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('auth.logout'), [], ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $logoutResp->assertStatus(204);

        // Response should include Set-Cookie headers for session and XSRF token
        $cookies = $logoutResp->headers->getCookies();
        $cookieNames = array_map(function ($c) { return $c->getName(); }, $cookies);
        $this->assertTrue(in_array(config('session.cookie'), $cookieNames));
        $this->assertTrue(in_array('XSRF-TOKEN', $cookieNames));

        Auth::forgetGuards();
        $this->flushSession();

        $meResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('users.me'));

        $meResp->assertStatus(401);
    }

    /** @test */
    public function it_can_logout_all_sessions()
    {
        $result = $this->loginViaSession($this->user->email, 'password123');
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        $resp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('auth.logoutAll'), [], ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $resp->assertStatus(204);

        $cookies = $resp->headers->getCookies();
        $cookieNames = array_map(function ($c) { return $c->getName(); }, $cookies);
        $this->assertTrue(in_array(config('session.cookie'), $cookieNames));
        $this->assertTrue(in_array('XSRF-TOKEN', $cookieNames));

        Auth::forgetGuards();
        $this->flushSession();

        $meResp = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('users.me'));

        $meResp->assertStatus(401);
    }

    /**
     * Helper to get CSRF cookie and perform login using cookies (SPA flow).
     * Returns array: ['response' => $response, 'xsrf' => $xsrfCookie, 'session_cookie' => $sessionCookie]
     */
    // loginViaSession is provided by Tests\TestCase

    /** @test */
    public function it_can_send_reset_password_code()
    {
        $response = $this->postJson(route('auth.sendResetCode'), ['email' => $this->user->email]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_reset_password()
    {
        $this->postJson(route('auth.sendResetCode'), ['email' => $this->user->email]);

        $resetCode = PasswordReset::where('email', $this->user->email)->first()->token;

        $response = $this->postJson(route('auth.resetPassword'), [
            'email' => $this->user->email,
            'code' => $resetCode,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_verify_admin_user_creation()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->adminUser->email,
            'user_type_id' => $this->adminType->id,
        ]);
    }

    /** @test */
    public function it_can_verify_cook_user_creation()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->cookUser->email,
            'user_type_id' => $this->cookType->id,
        ]);
    }
}

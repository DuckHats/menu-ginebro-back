<?php

namespace Tests\Feature;

use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
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
        $this->cookType = UserType::factory()->create(['id' => 3,'name' => 'cuina']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->userType->id,
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->adminType->id,
        ]);
        $this->adminToken = $this->adminUser->createToken('auth_token')->plainTextToken;

        $this->cookUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $this->cookType->id,
        ]);
        $this->cookToken = $this->cookUser->createToken('auth_token')->plainTextToken;
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
    public function it_can_login_a_user()
    {
        $loginData = [
            'user' => $this->user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_login_a_admin()
    {
        $loginData = [
            'user' => $this->adminUser->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_login_a_cooker()
    {
        $loginData = [
            'user' => $this->cookUser->email,
            'password' => 'password123',
        ];

        $response = $this->postJson(route('auth.login'), $loginData);

        $response->assertStatus(200);
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
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson(route('auth.logout'));

        $response->assertStatus(200);
    }

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

    /** @test */
    public function it_can_login_admin_user_with_token()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson(route('auth.login'), [
                'user' => $this->adminUser->email,
                'password' => 'password123',
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_login_cook_user_with_token()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->cookToken)
            ->postJson(route('auth.login'), [
                'user' => $this->cookUser->email,
                'password' => 'password123',
            ]);

        $response->assertStatus(200);
    }
}

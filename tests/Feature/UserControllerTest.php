<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $normalUser;

    protected $token;

    protected $adminUser;

    protected $adminToken;

    protected $cookUser;

    protected $cookToken;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);
        $userType = UserType::factory()->create(['id' => 2, 'name' => 'usuari']);
        $cookType = UserType::factory()->create(['id' => 3, 'name' => 'cuina']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $userType->id,
        ]);

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $adminType->id,
        ]);

        $this->cookUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'status' => User::STATUS_ACTIVE,
            'user_type_id' => $cookType->id,
        ]);
    }

    /** @test */
    public function it_can_list_users()
    {
        User::factory(5)->create();

        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type_id' => 2,
            'status' => User::STATUS_ACTIVE,
        ];

        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('users.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.show', $this->user->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_user_not_found()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_check_for_admin_user()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.adminCheck'));

        $response->assertStatus(200)
            ->assertJsonFragment(['admin' => true]);
    }

    /** @test */
    public function it_check_for_admin_user_fails()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson(route('users.adminCheck'));

        $response->assertStatus(200)
            ->assertJsonFragment(['admin' => false]);
    }

    /** @test */
    public function it_can_patch_a_user()
    {
        $updatedData = ['name' => 'Updated Name'];

        Sanctum::actingAs($this->user);

        $response = $this->patchJson(route('users.patch', $this->user->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson(route('users.destroy', $this->user->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

    /** @test */
    public function it_can_disable_user()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson(route('users.disable', $user->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_INACTIVE,
        ]);
    }

    /** @test */
    public function it_can_enable_user()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson(route('users.enable', $user->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function it_should_fail_if_i_want_to_disable_other_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson(route('users.disable', $this->cookUser->id));

        $response->assertStatus(500);
    }

    /** @test */
    public function it_can_export_users_in_json()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.export', ['format' => 'json']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_export_users_in_csv()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.export', ['format' => 'csv']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_export_users_in_xlsx()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->getJson(route('users.export', ['format' => 'xlsx']));
        $response->assertStatus(200);
    }
}

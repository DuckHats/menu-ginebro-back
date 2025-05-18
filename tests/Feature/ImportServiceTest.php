<?php

namespace Tests\Feature;

use App\Models\DishType;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ImportServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear tipus d'usuari admin
        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        // Crear usuari administrador i generar token
        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);

        $this->token = $this->adminUser->createToken('auth_token')->plainTextToken;
    }

    /** @test */
    public function it_imports_users_correctly()
    {
        $userType = UserType::factory()->create(['id' => 2]);

        $payload = [
            'format' => 'json',
            'data' => [
                [
                    'name' => 'Joan',
                    'last_name' => 'Garcia',
                    'email' => 'joan@example.com',
                    'password' => 'secret123',
                    'user_type_id' => $userType->id,
                    'status' => 1,
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('users.import'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'joan@example.com',
            'name' => 'Joan',
            'status' => 1,
        ]);
    }

    /** @test */
    public function it_fails_with_invalid_user_data()
    {
        $payload = [
            'format' => 'json',
            'data' => [
                [
                    'name' => '',
                    'email' => 'not-an-email',
                    'password' => '',
                    'user_type_id' => 9999,
                    'status' => 2,
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('users.import'), $payload);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_imports_menus_with_dishes()
    {
        $dishType = DishType::factory()->create(['id' => 1]);

        $payload = [
            'format' => 'json',
            'data' => [
                [
                    'day' => '2025-06-15',
                    'dishes' => [
                        [
                            'dish_date' => '2025-06-15',
                            'dish_type_id' => $dishType->id,
                            'options' => ['Paella', 'Amanida'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menus.import'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('menus', ['day' => '2025-06-15']);
        $this->assertTrue(
            DB::table('dishes')
                ->whereJsonContains('options', 'Paella')
                ->exists(),
            'La opció "Paella" no es troba dins del camp JSON options.'
        );
    }

    /** @test */
    public function it_fails_if_dishes_are_missing()
    {
        $payload = [
            'format' => 'json',
            'data' => [
                [
                    'day' => '2025-06-15',
                    'dishes' => [],
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menus.import'), $payload);

        $response->assertStatus(400);
    }
}

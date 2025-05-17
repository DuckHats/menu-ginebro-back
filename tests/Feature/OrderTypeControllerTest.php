<?php

namespace Tests\Feature;

use App\Models\OrderType;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderTypeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected $orderType;

    protected $order;

    protected $dishType;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    public function test_it_can_list_order_types()
    {
        OrderType::factory(5)->create();
        $response = $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->getJson(route('orderType.index'));

        $response->assertStatus(200);
    }
}

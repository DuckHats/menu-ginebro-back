<?php

namespace Tests\Feature;

use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderStatusControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);
    }

    public function test_it_can_list_order_status()
    {
        OrderStatus::factory(5)->create();
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('orderStatus.index'));

        $response->assertStatus(200);
    }
}

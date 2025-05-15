<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\UserType;
use App\Models\OrderType;
use App\Models\OrderStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;
    protected $order;
    protected $orderType;
    protected $orderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        // Crear un usuario autenticado
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Crear un tipo de pedido y estado de pedido
        $this->orderType = OrderType::factory()->create(['id' => 1, 'name' => 'Delivery']);
        $this->orderStatus = OrderStatus::factory()->create(['id' => 1, 'name' => 'Pending']);

        // Crear un pedido para las pruebas
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'order_date' => now(),
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ]);
    }

    /** @test */
    public function it_can_list_orders()
    {

        Order::factory(5)->create([
            'user_id' => $this->user->id,
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        $orderData = [
            'user_id' => $this->user->id,
            'order_date' => '2025-05-15',
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
            'allergies' => 'None',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.store'), $orderData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'order_date' => '2025-05-15',
            'order_type_id' => $this->orderType->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.store'), []);

        $response->assertStatus(400)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonPath('error.details.user_id.0', 'validation.required')
            ->assertJsonPath('error.details.order_date.0', 'validation.required')
            ->assertJsonPath('error.details.order_type_id.0', 'validation.required')
            ->assertJsonPath('error.details.order_status_id.0', 'validation.required');

                    // $response->assertStatus(422)
        //     ->assertJsonValidationErrors([
        //         'user_id',
        //         'order_type_id',
        //         'order_status_id',
        //     ]);
    }


    /** @test */
    public function it_can_show_an_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', $this->order->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['order_type_id' => $this->orderType->id]);
    }

    /** @test */
    public function it_returns_404_if_order_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_order()
    {
        $updatedData = [
            'user_id' => $this->user->id,
            'order_date' => '2025-05-16',
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('orders.update', $this->order->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'order_date' => '2025-05-16',
            'order_type_id' => $this->orderType->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_updating_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('orders.update', $this->order->id), []);


        $response->assertStatus(400)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonPath('error.details.user_id.0', 'validation.required')
            ->assertJsonPath('error.details.order_type_id.0', 'validation.required')
            ->assertJsonPath('error.details.order_status_id.0', 'validation.required');


        // $response->assertStatus(422)
        //     ->assertJsonValidationErrors([
        //         'user_id',
        //         'order_type_id',
        //         'order_status_id',
        //     ]);
    }

    /** @test */
    public function it_can_patch_an_order()
    {
        $updatedData = ['order_status_id' => $this->orderStatus->id];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patchJson(route('orders.patch', $this->order->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['order_status_id' => $this->orderStatus->id]);
    }

    /** @test */
    public function it_can_delete_an_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('orders.destroy', $this->order->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\DishType;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $token;

    protected $orderType;

    protected $orderStatus;

    protected $order;

    protected $dishes;

    protected $menu;

    protected $dishType;

    protected function setUp(): void
    {
        parent::setUp();

        // Creamos el tipo de usuario administrador
        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        // Creamos usuario y token
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);
        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Creamos menus para usar en los platos
        $this->menu = Menu::factory(5)->create([
            'day' => now(),
        ]);

        // Creamos tipos de platos
        $this->dishType = DishType::factory()->create([
            'id' => fake()->unique()->numberBetween(),
            'name' => 'Primer plat',
        ]);

        $this->dishes = Dish::factory(5)->create([
            'menu_id' => $this->menu->first()->id,
            'dish_type_id' => $this->dishType->first()->id,
            'options' => json_encode(['option1', 'option2']),
        ]);
        // Creamos tipos y estados de orden
        $this->orderType = OrderType::factory()->create(['id' => 1, 'name' => 'Primer plat + Postre']);
        $this->orderStatus = OrderStatus::factory()->create(['id' => 1, 'name' => 'Pendent']);

        // Creamos una orden para usar en tests
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'order_date' => now(),
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ]);
    }

    public function test_it_can_list_orders()
    {
        Order::factory(5)->create([
            'user_id' => $this->user->id,
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.index'));

        $response->assertStatus(200);
    }

    public function test_it_can_list_orders_by_date()
    {
        $date = now()->format('Y-m-d');
        Order::factory(3)->create([
            'user_id' => $this->user->id,
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
            'order_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.ordersbyDate', $date));

        $response->assertStatus(200);
    }

    public function test_it_can_list_orders_by_user()
    {
        Order::factory(3)->create([
            'user_id' => $this->user->id,
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.ordersbyUser', $this->user->id));

        $response->assertStatus(200);
    }

    public function test_it_can_create_an_order()
    {
        $dish1 = Dish::factory()->create([
            'menu_id' => $this->menu->first()->id,
            'dish_type_id' => $this->dishType->first()->id,
            'options' => json_encode(['option1', 'option2']),
        ]);

        $dish2 = Dish::factory()->create([
            'menu_id' => $this->menu->first()->id,
            'dish_type_id' => $this->dishType->first()->id,
            'options' => json_encode(['option1', 'option2']),
        ]);

        $orderData = [
            'user_id' => $this->user->id,
            'order_date' => '2025-05-15',
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
            'allergies' => 'None',
            'dish_ids' => [$dish1->id, $dish2->id],
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.store'), $orderData);

        $response->assertStatus(201)
            ->assertJsonFragment(['order_date' => '2025-05-15']);

        $this->assertDatabaseHas('orders', [
            'order_date' => '2025-05-15',
            'order_type_id' => $this->orderType->id,
        ]);
    }

    public function test_it_validates_required_fields_when_creating_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.store'), []);

        $response->assertStatus(400);
    }

    public function test_it_can_show_an_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', $order->id));

        $response->assertStatus(200);
    }

    public function test_it_returns_404_if_order_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', 9999));

        $response->assertStatus(404);
    }

    public function test_it_can_update_order_status()
    {
        OrderStatus::factory()->create(['id' => 2, 'name' => 'Preparant']);
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.updateStatus', $this->order->id), [
                'order_status_id' => 2,
            ]);

        $response->assertStatus(200);
    }

    public function test_it_can_export_orders_in_json()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.export', ['format' => 'json']));
        $response->assertStatus(200);
    }

    public function test_it_can_export_orders_in_excel()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.export', ['format' => 'xlsx']));
        $response->assertStatus(200);
    }

    public function test_it_can_export_orders_in_csv()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.export', ['format' => 'csv']));
        $response->assertStatus(200);
    }
}

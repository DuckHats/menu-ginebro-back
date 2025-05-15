<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\DishType;
use App\Models\Menu;
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
            'month' => now()->month,
            'week' => 'Setmana 1',
            'start_date' => now(),
            'end_date' => now()->addDays(7),
        ]);

        // Creamos tipos de platos
        $this->dishType = DishType::factory()->create([
            'id' => fake()->unique()->numberBetween(),
            'name' => 'Primer plat',
        ]);


        $this->dishes = Dish::factory(5)->create([
            'menu_id' => $this->menu->first()->id,
            'dish_date' => now(),
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

        $response->assertStatus(200)
                 ->assertJsonStructure(); // Puedes mejorar aquí si quieres
    }

    public function test_it_can_create_an_order()
    {
        // Usamos IDs reales de los platos creados en setUp
        $dishIds = $this->dishes->pluck('id')->take(2)->toArray();

        $orderData = [
            'user_id' => $this->user->id,
            'order_date' => '2025-05-15',
            'order_type_id' => $this->orderType->id,
            'order_status_id' => $this->orderStatus->id,
            'allergies' => 'None',
            'dish_ids' => $dishIds,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('orders.store'), $orderData);

        // dd($response->getContent()); // Descomenta si quieres debug

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

        $response->assertStatus(422)
            // ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ;    
        }

    public function test_it_can_show_an_order()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', $this->order->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['order_type_id' => $this->orderType->id]);
    }

    public function test_it_returns_404_if_order_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('orders.show', 9999));

        $response->assertStatus(404);
    }

    // Descomentar si quieres probar la actualización de una orden
//     public function test_it_can_update_an_order()
//     {
//         $updatedData = [
//             'user_id' => $this->user->id,
//             'order_date' => '2025-05-16',
//             'order_type_id' => $this->orderType->id,
//             'order_status_id' => $this->orderStatus->id,
//         ];

//         $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
//             ->putJson(route('orders.update', $this->order->id), $updatedData);
// dd($response->getContent()); // Descomenta si quieres debug
//         $response->assertStatus(200);
//         $this->assertDatabaseHas('orders', [
//             'order_date' => '2025-05-16',
//             'order_type_id' => $this->orderType->id,
//         ]);
//     }

//     public function test_it_validates_required_fields_when_updating_order()
//     {
//         $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
//             ->putJson(route('orders.update', $this->order->id), []);

//         $response->assertStatus(400)
//             ->assertJsonPath('error.code', 'VALIDATION_ERROR')
//             ->assertJsonPath('error.details.user_id.0', 'validation.required')
//             ->assertJsonPath('error.details.order_type_id.0', 'validation.required')
//             ->assertJsonPath('error.details.order_status_id.0', 'validation.required');
//     }

//     public function test_it_can_patch_an_order()
//     {
//         $updatedData = ['order_status_id' => $this->orderStatus->id];

//         $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
//             ->patchJson(route('orders.patch', $this->order->id), $updatedData);

//         $response->assertStatus(200);
//         $this->assertDatabaseHas('orders', ['order_status_id' => $this->orderStatus->id]);
//     }

//     public function test_it_can_delete_an_order()
//     {
//         $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
//             ->deleteJson(route('orders.destroy', $this->order->id));

//         $response->assertStatus(204);
//         $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
//     }
}

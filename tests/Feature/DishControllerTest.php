<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserType;
use App\Models\DishType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DishControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;
    protected $dish;
    protected $dishType;

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

        // Crear un tipo de plato y un plato para las pruebas
        $menu = Menu::factory()->create();
        $this->dishType = DishType::factory()->create(['name' => 'Primer plat + Postre']);
        $this->dish = Dish::factory()->create([
            'menu_id' => $menu->id,
            'dish_type_id' => $this->dishType->id,
        ]);
    }

    /** @test */
    public function it_can_list_dishes()
    {
        $dish = Dish::factory(5)->create([
            'menu_id' => $this->dish->menu_id,
            'dish_type_id' => $this->dishType->id,
        ]);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('dishes.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_dish_with_dish_type()
    {
        $dishData = [
            'menu_id' => $this->dish->menu_id,
            'dish_type_id' => $this->dishType->id,
            'options' => json_encode(['option1', 'option2']),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('dishes.store'), $dishData);


        $response->assertStatus(201);
        $this->assertDatabaseHas('dishes', [
            'menu_id' => $this->dish->menu_id,
            'dish_type_id' => $this->dishType->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_dish()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('dishes.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_dish_with_dish_type()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('dishes.show', $this->dish->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['dish_type_id' => $this->dishType->id]);
    }

    /** @test */
    public function it_returns_404_if_dish_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('dishes.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_dish_with_dish_type()
    {
        $updatedData = [
            'menu_id' => $this->dish->menu_id,
            'dish_type_id' => $this->dishType->id,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('dishes.update', $this->dish->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('dishes', [
            'menu_id' => $this->dish->menu_id,
            'dish_type_id' => $this->dishType->id,
        ]);
    }

    /** @test */
    public function it_can_patch_a_dish_with_dish_type()
    {
        $updatedData = ['dish_type_id' => $this->dishType->id];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patchJson(route('dishes.patch', $this->dish->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('dishes', ['dish_type_id' => $this->dishType->id]);
    }

    /** @test */
    public function it_can_delete_a_dish()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('dishes.destroy', $this->dish->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('dishes', ['id' => $this->dish->id]);
    }
}
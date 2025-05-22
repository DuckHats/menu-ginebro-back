<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MenuControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $menu;

    protected $user;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        // Crear un usuari autenticat
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Crear un menú per a les proves
        $this->menu = Menu::factory()->create();
    }

    /** @test */
    public function it_can_list_menus()
    {
        Menu::factory(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_menu()
    {
        $menuData = [
            'day' => '2025-01-01',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menus.store'), $menuData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-01']);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_menu()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menus.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_menu()
    {
        $menuDate = $this->menu->day;

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.show', $menuDate));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_menu_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.show', '0022-01-12'));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_menu()
    {
        $updatedData = ['day' => '2025-01-02'];
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('menus.update', $this->menu->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-02']);
    }

    /** @test */
    public function it_can_patch_a_menu()
    {
        $updatedData = ['day' => '2025-01-02'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patchJson(route('menus.patch', $this->menu->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-02']);
    }

    /** @test */
    public function it_can_delete_a_menu()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('menus.destroy', $this->menu->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('menus', ['id' => $this->menu->id]);
    }

    /** @test */
    public function it_can_export_menus_in_json()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.export', ['format' => 'json']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    /** @test */
    public function it_can_export_menus_in_excel()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.export', ['format' => 'xlsx']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function it_can_export_menus_in_csv()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menus.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }
}

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

        // use session-based auth in tests

        // Crear un menú per a les proves
        $this->menu = Menu::factory()->create();
    }

    /** @test */
    public function it_can_list_menus()
    {
        Menu::factory(5)->create();

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_menu()
    {
        $menuData = [
            'day' => '2025-01-01',
        ];

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->postJson(route('menus.store'), $menuData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-01']);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_menu()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->postJson(route('menus.store'), []);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_can_show_a_menu()
    {
        $menuDate = $this->menu->day;

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.show', $menuDate));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_menu_not_found()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.show', '0022-01-12'));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_menu()
    {
        $updatedData = ['day' => '2025-01-02'];
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->putJson(route('menus.update', $this->menu->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-02']);
    }

    /** @test */
    public function it_can_patch_a_menu()
    {
        $updatedData = ['day' => '2025-01-02'];

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->patchJson(route('menus.patch', $this->menu->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menus', ['day' => '2025-01-02']);
    }

    /** @test */
    public function it_can_delete_a_menu()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->deleteJson(route('menus.destroy', $this->menu->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('menus', ['id' => $this->menu->id]);
    }

    /** @test */
    public function it_can_export_menus_in_json()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.export', ['format' => 'json']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    /** @test */
    public function it_can_export_menus_in_excel()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.export', ['format' => 'xlsx']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function it_can_export_menus_in_csv()
    {
        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    /** @test */
    public function it_can_sort_menus_by_day()
    {
        Menu::query()->delete();
        Menu::factory()->create(['day' => '2025-01-05']);
        Menu::factory()->create(['day' => '2025-01-01']);

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.index', ['sort_by' => 'day', 'sort_order' => 'asc']));

        $response->assertStatus(200);
        $this->assertEquals('2025-01-01', $response->json('data.0.day'));

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.index', ['sort_by' => 'day', 'sort_order' => 'desc']));

        $response->assertStatus(200);
        $this->assertEquals('2025-01-05', $response->json('data.0.day'));
    }

    /** @test */
    public function it_can_paginate_menus()
    {
        Menu::query()->delete();
        Menu::factory(20)->create();

        $login = $this->loginViaSession($this->user, 'password123');
        $session = $login['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $session)
            ->getJson(route('menus.index', ['per_page' => 10]));

        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data'));
        $this->assertEquals(20, $response->json('meta.total'));
    }
}

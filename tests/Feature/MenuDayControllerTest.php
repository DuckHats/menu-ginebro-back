<?php

namespace Tests\Feature;

use App\Models\MenuDay;
use App\Models\Menu;
use App\Models\Day;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MenuDayControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $token;
    protected $menuDay;

    protected function setUp(): void
    {
        parent::setUp();

        $adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);

        // Create an authenticated user
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
        ]);

        $this->token = $this->user->createToken('auth_token')->plainTextToken;

        // Create related models for MenuDay
        $menu = Menu::factory()->create();
        $day = Day::factory()->create();

        // Create a MenuDay for testing
        $this->menuDay = MenuDay::factory()->create([
            'menu_id' => $menu->id,
            'day_id' => $day->id,
        ]);
    }

    /** @test */
    public function it_can_list_menu_days()
    {
        MenuDay::factory(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menu_days.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_menu_day()
    {
        $menu = Menu::factory()->create();
        $day = Day::factory()->create();

        $menuDayData = [
            'menu_id' => $menu->id,
            'day_id' => $day->id,
            'specific_date' => '2023-12-25',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menu_days.store'), $menuDayData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('menu_days', ['specific_date' => '2023-12-25']);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_menu_day()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('menu_days.store'), []);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_show_a_menu_day()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menu_days.show', $this->menuDay->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_if_menu_day_not_found()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menu_days.show', 9999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_menu_day()
    {
        $updatedData = ['specific_date' => '2023-12-31'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson(route('menu_days.update', $this->menuDay->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menu_days', ['specific_date' => '2023-12-31']);
    }

    /** @test */
    public function it_can_patch_a_menu_day()
    {
        $updatedData = ['specific_date' => '2024-01-01'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patchJson(route('menu_days.patch', $this->menuDay->id), $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('menu_days', ['specific_date' => '2024-01-01']);
    }

    /** @test */
    public function it_can_delete_a_menu_day()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson(route('menu_days.destroy', $this->menuDay->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('menu_days', ['id' => $this->menuDay->id]);
    }

    /** @test */
    public function it_can_export_menu_days()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('menu_days.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }
}

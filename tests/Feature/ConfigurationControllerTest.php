<?php

namespace Tests\Feature;

use App\Models\Configuration;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ConfigurationControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;
    protected $adminType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);
        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $this->adminType->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function it_can_list_configurations()
    {
        Configuration::updateOrCreate(['key' => 'test_key'], ['value' => 'test_value']);

        $result = $this->loginViaSession($this->adminUser);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('configurations.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['test_key' => 'test_value']);
    }

    /** @test */
    public function it_can_update_configurations()
    {
        $result = $this->loginViaSession($this->adminUser);
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        $data = [
            'settings' => [
                'menu_price' => '10.50',
                'taper_price' => '0.50'
            ]
        ];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('configurations.update'), $data, ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $response->assertStatus(200);

        $this->assertEquals('10.50', Configuration::where('key', 'menu_price')->first()->value);
        $this->assertEquals('0.50', Configuration::where('key', 'taper_price')->first()->value);
    }

    /** @test */
    public function it_fails_to_update_with_invalid_data()
    {
        $result = $this->loginViaSession($this->adminUser);
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('configurations.update'), ['settings' => 'not-an-array'], ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $response->assertStatus(422);
    }
}

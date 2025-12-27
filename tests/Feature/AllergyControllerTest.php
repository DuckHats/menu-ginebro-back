<?php

namespace Tests\Feature;

use App\Models\Allergy;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AllergyControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $userType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userType = UserType::factory()->create(['id' => 2, 'name' => 'usuari']);
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $this->userType->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function it_can_list_allergies()
    {
        Allergy::factory()->count(3)->create();

        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $result['xsrf'])
            ->getJson(route('allergies.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description']
                ]
            ]);
    }

    /** @test */
    public function it_can_update_user_allergies()
    {
        $allergies = Allergy::factory()->count(2)->create();
        $allergyIds = $allergies->pluck('id')->toArray();
        $customAllergies = 'Maduixes, Kiwi';

        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('allergies.update'), [
                'allergies' => $allergyIds,
                'custom_allergies' => $customAllergies,
            ], ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $response->assertStatus(200);
        $this->user->refresh();

        $this->assertEquals($customAllergies, $this->user->custom_allergies);
        $this->assertCount(2, $this->user->allergies);
        $this->assertEquals($allergyIds, $this->user->allergies->pluck('id')->toArray());
    }

    /** @test */
    public function it_fails_to_update_allergies_if_not_authenticated()
    {
        $response = $this->postJson(route('allergies.update'), [
            'allergies' => [],
            'custom_allergies' => 'None',
        ]);

        $response->assertStatus(401);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $adminUser;
    protected $userType;
    protected $adminType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminType = UserType::factory()->create(['id' => 1, 'name' => 'administrador']);
        $this->userType = UserType::factory()->create(['id' => 2, 'name' => 'usuari']);

        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $this->userType->id,
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->adminUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $this->adminType->id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function it_can_list_own_transactions()
    {
        Transaction::factory()->count(3)->create(['user_id' => $this->user->id]);
        Transaction::factory()->count(2)->create(); // Other users

        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.index'));

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_can_sort_transactions()
    {
        Transaction::factory()->create(['user_id' => $this->user->id, 'amount' => 10, 'created_at' => now()->subDay()]);
        Transaction::factory()->create(['user_id' => $this->user->id, 'amount' => 50, 'created_at' => now()]);

        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.index', ['sort_by' => 'amount', 'sort_order' => 'asc']));

        $response->assertStatus(200);
        $this->assertEquals(10, $response->json('data.0.amount'));

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.index', ['sort_by' => 'amount', 'sort_order' => 'desc']));

        $this->assertEquals(50, $response->json('data.0.amount'));
    }

    /** @test */
    public function it_can_list_all_transactions_as_admin()
    {
        Transaction::factory()->count(5)->create();

        $result = $this->loginViaSession($this->adminUser);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.admin'));

        $response->assertStatus(200);
        // Might be more if seeders ran, but at least 5
        $this->assertGreaterThanOrEqual(5, count($response->json('data')));
    }

    /** @test */
    public function it_cannot_list_all_transactions_as_regular_user()
    {
        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.admin'));

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_search_transactions_as_admin()
    {
        $user = User::factory()->create(['name' => 'UniqueName']);
        Transaction::factory()->create(['user_id' => $user->id, 'description' => 'Target Transaction']);
        Transaction::factory()->count(2)->create(['description' => 'Other']);

        $result = $this->loginViaSession($this->adminUser);
        $sessionCookie = $result['session_cookie'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->getJson(route('transactions.admin', ['search' => 'UniqueName']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Target Transaction', $response->json('data.0.description'));
    }
}

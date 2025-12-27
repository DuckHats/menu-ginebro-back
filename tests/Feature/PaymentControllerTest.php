<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserType;
use App\Services\RedsysService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class PaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $redsysService;

    protected function setUp(): void
    {
        parent::setUp();

        $userType = UserType::factory()->create(['id' => 2, 'name' => 'usuari']);
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
            'user_type_id' => $userType->id,
            'status' => User::STATUS_ACTIVE,
            'balance' => 0,
        ]);

        $this->redsysService = Mockery::mock(RedsysService::class);
        $this->app->instance(RedsysService::class, $this->redsysService);
    }

    /** @test */
    public function it_can_initiate_payment()
    {
        $this->redsysService->shouldReceive('getPaymentParameters')
            ->once()
            ->andReturn([
                'url' => 'https://sis.redsys.es/sis/realizarPago',
                'params' => 'base64params',
                'signature' => 'signature',
            ]);

        $result = $this->loginViaSession($this->user);
        $sessionCookie = $result['session_cookie'];
        $xsrf = $result['xsrf'];

        $response = $this->withCookie(config('session.cookie'), $sessionCookie)
            ->withCookie('XSRF-TOKEN', $xsrf)
            ->postJson(route('payment.initiate'), [
                'amount' => 10.00,
            ], ['X-XSRF-TOKEN' => urldecode($xsrf)]);

        $response->assertStatus(200)
            ->assertJsonStructure(['url', 'params', 'signature']);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'amount' => 10.00,
            'status' => 'pending',
            'type' => Transaction::TYPE_TOPUP,
        ]);
    }

    /** @test */
    public function it_processes_redsys_notification_successfully()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 20.00,
            'status' => 'pending',
            'order_id' => 'Order123',
        ]);

        $params = base64_encode(json_encode([
            'Ds_Order' => 'Order123',
            'Ds_Response' => '0000', // Authorized
            'Ds_AuthorisationCode' => '123456',
        ]));

        $this->redsysService->shouldReceive('decodeParams')
            ->once()
            ->with($params)
            ->andReturn([
                'Ds_Order' => 'Order123',
                'Ds_Response' => '0000',
                'Ds_AuthorisationCode' => '123456',
            ]);

        // Mocking the signature verification is harder because it's in the controller using private methods
        // and redundant logic. We can mock the RedsysService to return the decoded params,
        // but we need to pass isValidSignature in the controller.

        // Actually, let's look at how isValidSignature is implemented.
        // It calls $this->redsysService->validateSignature($params, $signature)

        $this->redsysService->shouldReceive('checkSignature')
            ->once()
            ->andReturn(true);

        $response = $this->postJson(route('payment.notify'), [
            'Ds_MerchantParameters' => $params,
            'Ds_Signature' => 'dummy_signature',
        ]);
        $response->assertStatus(200)
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed',
            'response_code' => 0,
            'authorization_code' => '123456',
        ]);

        $this->user->refresh();
        $this->assertEquals(20.00, $this->user->balance);
    }

    /** @test */
    public function it_handles_failed_redsys_notification()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 20.00,
            'status' => 'pending',
            'order_id' => 'OrderFailed',
        ]);

        $params = base64_encode(json_encode([
            'Ds_Order' => 'OrderFailed',
            'Ds_Response' => '0101', // Denied
        ]));

        $this->redsysService->shouldReceive('decodeParams')
            ->once()
            ->andReturn([
                'Ds_Order' => 'OrderFailed',
                'Ds_Response' => '0101',
            ]);

        $this->redsysService->shouldReceive('checkSignature')
            ->once()
            ->andReturn(true);

        $response = $this->postJson(route('payment.notify'), [
            'Ds_MerchantParameters' => $params,
            'Ds_Signature' => 'dummy_signature',
        ]);

        $response->assertStatus(200); // Usually redsys expects 200 even on payment failure to stop notifying

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'failed',
            'response_code' => 101,
        ]);

        $this->user->refresh();
        $this->assertEquals(0, $this->user->balance);
    }
}

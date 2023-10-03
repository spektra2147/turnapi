<?php

namespace Tests\Unit\Services;

use App\Http\Requests\CallbackRequest;
use App\Services\CallbackService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CallbackServiceTest extends TestCase
{
    public function test_sendCallbackRequest_success()
    {
        // Mock a callback request
        $request = new CallbackRequest([
            'price' => '0.02',
            'point' => '0.00',
            'order_id' => '3307333742',
            'user_id' => '3b43544b-10b4-45e2-ab81-2b2b3f1917e8',
            'user_phone' => '905555555555',
            'ref_code' => '0781534070',
            'callback_success_url' => 'https://api.altpay.dev/tag-qr/3307333742/success',
            'callback_fail_url' => 'https://api.altpay.dev/tag-qr/3307333742/fail',
            'hash' => '9fe2ea5ad3f1f5b0289b151521ed1ed2d772dae0'
        ]);

        // Mock HTTP post response for success
        Http::fake([
            'https://api.altpay.dev/tag-qr/3307333742/success' => Http::response(['success' => true], 200)
        ]);

        $callbackService = new CallbackService();

        $response = $callbackService->sendCallbackRequest($request, true);

        $this->assertTrue($response->successful());
        $this->assertEquals(['success' => true], $response->json());
    }

    public function test_sendCallbackRequest_failure()
    {
        $request = new CallbackRequest([
            'price' => '0.02',
            'point' => '0.00',
            'order_id' => '3307333742',
            'user_id' => '3b43544b-10b4-45e2-ab81-2b2b3f1917e8',
            'user_phone' => '905555555555',
            'ref_code' => '0781534070',
            'callback_success_url' => 'https://api.altpay.dev/tag-qr/3307333742/success',
            'callback_fail_url' => 'https://api.altpay.dev/tag-qr/3307333742/fail',
            'hash' => '9fe2ea5ad3f1f5b0289b151521ed1ed2d772dae0'
        ]);

        // Mock HTTP post response for failure
        Http::fake([
            'https://api.altpay.dev/tag-qr/3307333742/fail' => Http::response(['success' => false], 500)
        ]);

        // Create CallbackService instance
        $callbackService = new CallbackService();

        // Call the method and assert the response
        $response = $callbackService->sendCallbackRequest($request, false);

        $this->assertFalse($response->successful());
        $this->assertEquals(['success' => false], $response->json());
    }
}

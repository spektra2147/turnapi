<?php

namespace Tests\Unit\Services;

use App\Http\Requests\CallbackRequest;
use App\Services\CallbackService;
use App\Services\HashService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CallbackServiceTest extends TestCase
{
    public function test_sendCallbackRequest_success()
    {
        // Mock a callback request
        $request = new CallbackRequest([
            "price" => "55.33",
            "point" => "0.00",
            "order_id" => "3307333742",
            "user_id" => "3b43544b-10b4-45e2-ab81-2b2b3f1917e8",
            "user_phone" => "905555555555",
            "ref_code" => "0781534070",
            "callback_success_url" => "https://case.altpay.dev/success",
            "callback_fail_url" => "https://case.altpay.dev/fail",
            "hash" => "2918f946ce80bd37e7dbf4ade4888df9d281de0d"
        ]);

        // Mock HTTP post response for success
        Http::fake([
            'https://case.altpay.dev/success' => Http::response(['success' => true], 200)
        ]);

        $callbackService = new CallbackService(new HashService());

        $response = $callbackService->sendCallbackRequest($request, true);

        $this->assertTrue($response->successful());
        $this->assertEquals(['success' => true], $response->json());
    }

    public function test_sendCallbackRequest_failure()
    {
        $request = new CallbackRequest([
            "price" => "55.33",
            "point" => "0.00",
            "order_id" => "3307333742",
            "user_id" => "3b43544b-10b4-45e2-ab81-2b2b3f1917e8",
            "user_phone" => "905555555555",
            "ref_code" => "0781534070",
            "callback_success_url" => "https://case.altpay.dev/success",
            "callback_fail_url" => "https://case.altpay.dev/fail",
            "hash" => "2918f946ce80bd37e7dbf4ade4888df9d281de0d"
        ]);

        // Mock HTTP post response for failure
        Http::fake([
            'https://case.altpay.dev/fail' => Http::response(['success' => false], 500)
        ]);

        // Create CallbackService instance
        $callbackService = new CallbackService(new HashService());

        // Call the method and assert the response
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Callback request failed');
        $callbackService->sendCallbackRequest($request, false);
    }
}

<?php

namespace Tests\Unit\Services;

use App\Http\Requests\CallbackRequest;
use App\Services\HashService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class HashServiceTest extends TestCase
{
    public function test_validateHash_valid()
    {
        Config::shouldReceive('get')->with('macellan.salt')->andReturn('test');

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

        // Create HashService instance
        $hashService = new HashService();

        // Call the method and assert the result
        $isValid = $hashService->validateHash($request);

        $this->assertTrue($isValid);
    }

    public function test_generateCallbackHash()
    {
        Config::shouldReceive('get')->with('macellan.salt')->andReturn('test');

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

        // Create HashService instance
        $hashService = new HashService();

        // Call the method and assert the result
        $hash = $hashService->generateCallbackHash($request);

        $request['hash'] = $hash;
        $isValid = $hashService->validateHash($request);

        $this->assertTrue($isValid);
    }
}

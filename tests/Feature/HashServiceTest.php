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
        Config::shouldReceive('get')->with('services.macellan.salt')->andReturn('test');

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
            "hash" => "a7b8503636be2741d9d220f7b5aca6b6aca88f81"
        ]);

        $hashService = new HashService();

        $isValid = $hashService->validateHash($request);

        $this->assertTrue($isValid);
    }

    public function test_generateCallbackHash()
    {
        Config::shouldReceive('get')->with('services.macellan.salt')->andReturn('test');

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
            "hash" => "848c5d4b7ccb4ba981df8256304193290f53b26f"
        ]);

        $hashService = new HashService();
        $hash = $hashService->generateCallbackHash($request);

        $this->assertTrue($request->get('hash') === $hash);
    }
}

<?php

namespace App\Services;

use App\Http\Requests\CallbackRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallbackService
{
    private $hashService;

    public function __construct(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    public function sendCallbackRequest(CallbackRequest $request, $success = true)
    {
        try {
            $hash = $this->hashService->generateCallbackHash($request);
            $callbackUrl = $success ? $request->get('callback_success_url') : $request->get('callback_fail_url');

            $response = Http::post($callbackUrl, [
                'hash' => $hash
            ], [
                'Content-Type' => 'application/json'
            ]);

            if (!$response->successful()) {
                throw new \Exception(json_decode($response->body(),true)['message'], $response->status());
            }

            return $response;

        } catch (\Exception $e) {

            Log::error('Callback request failed. Error: ' . $e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}

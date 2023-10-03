<?php

namespace App\Services;

use App\Http\Requests\CallbackRequest;
use Illuminate\Support\Facades\Config;

class HashService
{
    private $salt;

    public function __construct()
    {
        $this->salt = Config::get('services.macellan.salt');
    }

    public function validateHash(CallbackRequest $request)
    {
        $cHash = sha1(sprintf(
            '%s%s%s%s',
            $this->salt,
            $request->get('callback_fail_url'),
            $request->get('callback_success_url'),
            $request->get('price'),
        ));

        return $request['hash'] === $cHash;
    }

    public function generateCallbackHash(CallbackRequest $request)
    {
        $cHash = sha1(sprintf(
            '%s%s%s%s',
            $request->get('price'),
            $request->get('callback_success_url'),
            $request->get('callback_fail_url'),
            $this->salt,
        ));

        return $cHash;
    }
}

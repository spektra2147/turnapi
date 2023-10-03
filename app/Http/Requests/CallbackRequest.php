<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // İsteği her zaman kabul et
    }

    public function rules()
    {
        return [
            'price' => 'required|numeric',
            'point' => 'required|numeric',
            'order_id' => 'required|string',
            'user_id' => 'required|string',
            'user_phone' => 'required|string|regex:/^\d{10,15}$/',
            'ref_code' => 'required|string',
            'callback_success_url' => 'required|url',
            'callback_fail_url' => 'required|url',
            'hash' => 'required|string',
        ];
    }
}

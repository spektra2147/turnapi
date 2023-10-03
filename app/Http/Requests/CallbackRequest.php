<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Justfeel\Response\ResponseCodes;

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

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], ResponseCodes::HTTP_BAD_REQUEST)
        );
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Exceptions\HttpResponseException;
// use App\Helpers\ApiResponse;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    // protected function failedValidation($validator)
    // {
    //     throw new HttpResponseException(
    //         ApiResponse::error('Validation errors', $validator->errors())
    //     );
    // }
}

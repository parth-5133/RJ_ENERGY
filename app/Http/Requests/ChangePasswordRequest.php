<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => 'required|string|min:3',
            'password_confirmation' => 'required|string|same:password|min:3',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

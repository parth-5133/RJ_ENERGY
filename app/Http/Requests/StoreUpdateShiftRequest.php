<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateShiftRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shift_name' => 'required|string|max:255',
            'from_time' => 'required|string',
            'to_time' => 'required|string',
            'is_active' => 'boolean|nullable',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}
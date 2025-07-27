<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLeaveTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'leave_type_name' => 'required|string|max:255',
            'max_days' => 'required|integer',
            'carry_forward_max_balance' => 'nullable|integer',
            'expiry_date' => 'nullable|date',
            'is_currentYear' => 'boolean|nullable',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

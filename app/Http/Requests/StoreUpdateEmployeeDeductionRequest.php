<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StoreUpdateEmployeeDeductionRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'deductionId' => 'nullable|integer',
            'deduction_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

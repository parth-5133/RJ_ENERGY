<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class CreateFinancialYearRequest extends FormRequest
{
    public function rules()
    {
        return [
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'display_year' => 'required|string|max:20',
            'is_currentYear' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

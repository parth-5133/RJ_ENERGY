<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
class StoreEmployeeEducationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'highest_degree' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'year_of_graduation' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'certifications' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:1000',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

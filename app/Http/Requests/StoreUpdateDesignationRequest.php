<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StoreUpdateDesignationRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'designationId' => 'nullable|integer',
            'name' => 'required|string|max:255',
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

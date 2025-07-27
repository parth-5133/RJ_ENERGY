<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePolicyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'policyId' => 'nullable|integer',
            'policy_name' => 'required|string|max:255',
            'policy_description' => 'nullable|string',
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:effective_date',
            'policy_document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',
            'issued_by' => 'nullable|string|max:255',
            'is_active' => 'boolean|nullable',
            'display_to_employee' => 'boolean|nullable',
            'display_to_client' => 'boolean|nullable',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

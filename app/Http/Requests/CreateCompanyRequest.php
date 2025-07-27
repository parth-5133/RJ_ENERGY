<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

class CreateCompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'legal_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'logo_url' => 'nullable|max:255',
            'alternate_mobile_no' => 'nullable|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'address_line_3' => 'nullable|string|max:255',
            'country' => 'required|integer|exists:countries,id',
            'state' => 'required|integer|exists:states,id',
            'city' => 'required|string|max:100',
            'pin_code' => 'required|string|max:10',
            'is_active' => 'nullable|boolean',
            'website' => 'nullable|url|max:255',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

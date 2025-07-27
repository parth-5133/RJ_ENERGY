<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
class StorePersonalInfoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'personal_email' => 'required|email|max:255',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:50',
            'gender' => 'required|in:m,f,o',
            'phone_number' => 'required|digits:10',
            'emergency_phone_number' => 'required|digits:10',
            'alternate_phone_number' => 'nullable|digits:10',
            'marital_status_id' => 'required|exists:marital_statuses,id',
            'nationality_id' => 'required|exists:countries,id',
            'disability_status' => 'required|in:0,1,2,3',
            'citizenship' => 'nullable|string',
            'religion' => 'nullable|string',
            'blood_group' => 'nullable|string',
            'aadhaar_number' => 'nullable|string|max:12',
            'pan_number' => 'nullable|string|max:10',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

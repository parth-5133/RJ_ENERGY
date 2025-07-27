<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class StoreEmployeeExperienceRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'id' => 'nullable',
            'organization_name' => 'required|max:255',
            'from_date' => 'required',
            'to_date' => 'required',
            'designation' => 'required|max:255',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'experience_letter' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

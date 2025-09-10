<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreEmployeeResignationRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'employee_id' => 'nullable|integer',
            'resignationId' => 'nullable|integer',
            'resignation_date' => 'nullable|date',
            'reason' => 'nullable|string',
            'status' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:2048',
            'last_working_date' => [
                'nullable',
                'date',
                'after_or_equal:resignation_date',
                Rule::requiredIf(function () use ($request) {
                    return $request->input('status') === 'Approved';
                }),
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateHolidayRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        return [
            'holiday_id' => 'nullable',
            'holiday_date' => [
                'required',
                'date',
                Rule::unique('holidays', 'holiday_date')->ignore($this->input('holiday_id'))->whereNull('deleted_at'),
            ],
            'holiday_name' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'holiday_date.required' => 'The holiday date is required.',
            'holiday_date.date' => 'The holiday date must be a valid date.',
            'holiday_name.required' => 'The holiday name is required.',
            'holiday_name.string' => 'The holiday name must be a string.',
            'holiday_name.max' => 'The holiday name must not exceed 50 characters.',
            'holiday_date' => 'The holiday date already exists.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

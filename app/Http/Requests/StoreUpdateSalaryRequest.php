<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateSalaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'EMployeeRoleId' => ['nullable', 'integer'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'employee' => ['required', 'integer', 'exists:users,id'],
            'salary_month' => ['required', 'string', 'in:01,02,03,04,05,06,07,08,09,10,11,12'],
            'salary_year' => ['required', 'integer'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['array'],
            'allowances.*.name' => ['nullable', 'string'],
            'allowances.*.value' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['array'],
            'deductions.*.name' => ['nullable', 'string'],
            'deductions.*.value' => ['nullable', 'numeric', 'min:0'],
            'total_allowances' => ['required', 'numeric', 'min:0'],
            'total_deductions' => ['required', 'numeric', 'min:0'],
            'total_salary' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;


class StoreBankDetailsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|numeric|digits_between:10,18',
            'ifsc_code' => 'required|string|max:11',
            'swift_code' => 'required|string|max:11',
            'base_salary' => 'nullable|string|max:255',
            'bonus_eligibility' => 'nullable|string|max:255',
            'pay_grade' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:255',
            'payment_mode' => 'nullable|string|max:255',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

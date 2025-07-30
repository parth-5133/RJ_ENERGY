<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreManageBankRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'manageBankId' => 'nullable|integer',
            'bank_name' => 'required|string|max:50',
            'branch_name' => 'required|string|max:50',
            'branch_manager_phone' => 'required|digits_between:10,15',
            'loan_manager_phone' => 'required|digits_between:10,15',
            'ifsc_code' => 'required|string|max:11',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'bank_name.required' => 'Bank name is required',
            'branch_name.required' => 'Branch name is required',
            'branch_manager_phone.required' => 'Branch manager phone is required',
            'branch_manager_phone.digits_between' => 'Enter a valid phone number (10-15 digits)',
            'loan_manager_phone.required' => 'Loan manager phone is required',
            'loan_manager_phone.digits_between' => 'Enter a valid phone number (10-15 digits)',
            'ifsc_code.required' => 'IFSC code is required',
            'ifsc_code.max' => 'IFSC code cannot be more than 11 characters',
            'address.required' => 'Address is required',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

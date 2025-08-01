<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateQuotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quotesId' => 'nullable|integer',
            'customer_name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'mobile' => ['required', 'digits:10'],
            'alternate_mobile' => ['nullable', 'digits:10'],
            'aadhar' => ['required', 'digits:12'],
            'pan' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i'],
            'quotation_' => ['required', Rule::in(['Yes', 'No'])],
            'quotation_amount' => ['required_if:quotation_,Yes', 'numeric', 'min:0'],
            'quotation_date' => ['required_if:quotation_,Yes', 'date'],
            'quotation_by' => ['required_if:quotation_,Yes', 'string'],
            'quotation_status' => ['required_if:quotation_,Yes', Rule::in(['Pending', 'Agreed'])],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Customer name is required.',
            'age.required' => 'Age is required.',
            'mobile.required' => 'Mobile number is required.',
            'aadhar.required' => 'Aadhar number is required.',
            'quotation_.required' => 'Quotation selection is required.',
            'quotation_amount.required_if' => 'Quotation amount is required when quotation is Yes.',
            'quotation_date.required_if' => 'Quotation date is required when quotation is Yes.',
            'quotation_by.required_if' => 'Entered By is required when quotation is Yes.',
            'quotation_status.required_if' => 'Quotation status is required when quotation is Yes.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

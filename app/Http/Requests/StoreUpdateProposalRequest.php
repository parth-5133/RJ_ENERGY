<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateProposalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'solar_capacity'     => 'required|string|max:255',
            'roof_type'          => 'required|string|in:RCC,Tin,Slope,Other',
            'roof_area'          => 'required|numeric|min:1',
            'net_metering'       => 'required|in:Yes,No',
            'subsidy_claimed'    => 'required|in:Yes,No',
            'purchase_mode'      => 'required|in:Cash,Loan,EMI',
            'loan_required'      => 'required|in:Yes,No',
            'bank_name'          => 'required_if:loan_required,Yes|string|max:255',
            'bank_branch'        => 'required_if:loan_required,Yes|string|max:255',
            'account_number'     => 'required_if:loan_required,Yes|numeric',
            'ifsc_code'          => 'required_if:loan_required,Yes|string|max:20',
            'loan_mode'          => 'required_if:loan_required,Yes|string|in:Loan,EMI',
            'aadhaar_card'       => 'required|file',
            'pan_card'           => 'required|file',
            'electricity_bill'   => 'required|file',
            'bank_proof'         => 'required_if:loan_required,Yes|file',
            'passport_photo'     => 'required|file',
            'ownership_proof'    => 'required|file',
            'site_photo'         => 'required|file',
            'self_declaration'   => 'required|file',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'required_if' => 'The :attribute is required when loan is selected.',
            'file' => 'The :attribute must be a valid file.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max' => 'The :attribute must not be greater than :max kilobytes.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

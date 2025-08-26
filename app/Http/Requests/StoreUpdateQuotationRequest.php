<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateQuotationRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        return [
            'quotesId' => 'nullable|integer',
            'first_name'     => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'middle_name'     => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'pan_number'     => 'required',
            'aadhar_number'     => 'required',
            'age'               => 'required|integer|min:1',
            'mobile'            => 'required|string|max:15',
            'quotation_'        => 'required|in:Yes,No',
            'solar_capacity'    => 'required_if:quotation_,Yes',
            'rooftop_size'      => 'required_if:quotation_,Yes',
            'quotation_amount'  => 'required_if:quotation_,Yes|nullable|numeric',
            'quotation_date'    => 'required_if:quotation_,Yes|nullable|date',
            'quotation_by'      => 'required_if:quotation_,Yes',
            'quotation_status'  => 'required_if:quotation_,Yes',
        ];
    }

    public function messages()
    {
        return [
            'age.required'                 => 'Age is required.',
            'mobile.required'              => 'Mobile number is required.',
            'quotation_.required'          => 'Quotation selection is required.',
            'quotation_.in'                => 'Quotation must be either Yes or No.',
            'solar_capacity.required_if'  => 'Solar Capacity is required when quotation is Yes.',
            'rooftop_size.required_if'    => 'Rooftop Size is required when quotation is Yes.',
            'quotation_amount.required_if' => 'Quotation amount is required when quotation is Yes.',
            'quotation_date.required_if'  => 'Quotation date is required when quotation is Yes.',
            'quotation_by.required_if'    => 'Entered By is required when quotation is Yes.',
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAddressRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'PerAdd_type' => 'nullable|string|in:Permanent',
            'PerAdd_contact_name' => 'required|string|max:255',
            'PerAdd_address_line_1' => 'required|string|max:255',
            'PerAdd_address_line_2' => 'required|string|max:255',
            'PerAdd_address_line_3' => 'nullable|string|max:255',
            'PerAdd_country' => 'required|integer|exists:countries,id',
            'PerAdd_state' => 'required|integer|exists:states,id',
            'PerAdd_city' => 'required|string|max:255',
            'PerAdd_pin_code' => 'required|digits:6',
            'PerAdd_mobile_no' => 'required|digits:10',
            'PerAdd_alternate_mobile_no' => 'nullable|digits:10',
            'PerAdd_residing_from' => 'required|date',
            'PerAdd_area' => 'nullable|string|max:255',
            'PerAdd_landmark' => 'nullable|string|max:255',
            'PerAdd_latitude' => 'required|numeric',
            'PerAdd_longitude' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'PerAdd_contact_name.required' => 'The contact name is required.',
            'PerAdd_contact_name.string' => 'The contact name must be a string.',
            'PerAdd_contact_name.max' => 'The contact name may not be greater than 255 characters.',

            'PerAdd_address_line_1.required' => 'The first address line is required.',
            'PerAdd_address_line_1.string' => 'The first address line must be a string.',
            'PerAdd_address_line_1.max' => 'The first address line may not be greater than 255 characters.',

            'PerAdd_address_line_2.required' => 'The second address line is required.',
            'PerAdd_address_line_2.string' => 'The second address line must be a string.',
            'PerAdd_address_line_2.max' => 'The second address line may not be greater than 255 characters.',

            'PerAdd_country.required' => 'The country is required.',
            'PerAdd_country.integer' => 'The country must be an integer.',
            'PerAdd_country.exists' => 'The selected country is invalid.',

            'PerAdd_state.required' => 'The state is required.',
            'PerAdd_state.integer' => 'The state must be an integer.',
            'PerAdd_state.exists' => 'The selected state is invalid.',

            'PerAdd_city.required' => 'The city is required.',
            'PerAdd_city.string' => 'The city must be a string.',
            'PerAdd_city.max' => 'The city may not be greater than 255 characters.',

            'PerAdd_pin_code.required' => 'The pin code is required.',
            'PerAdd_pin_code.digits' => 'The pin code must be 6 digits.',

            'PerAdd_mobile_no.required' => 'The mobile number is required.',
            'PerAdd_mobile_no.digits' => 'The mobile number must be 10 digits.',

            'PerAdd_alternate_mobile_no.digits' => 'The alternate mobile number must be 10 digits.',

            'PerAdd_residing_from.required' => 'The residing from date is required.',
            'PerAdd_residing_from.date' => 'The residing from date is not a valid date.',

            'PerAdd_latitude.required' => 'The latitude is required.',
            'PerAdd_latitude.numeric' => 'The latitude must be a number.',

            'PerAdd_longitude.required' => 'The longitude is required.',
            'PerAdd_longitude.numeric' => 'The longitude must be a number.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

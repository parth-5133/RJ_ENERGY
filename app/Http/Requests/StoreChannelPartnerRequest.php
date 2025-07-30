<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreChannelPartnerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true to allow all users (adjust if needed)
    }

    public function rules()
    {
        return [
            'channelPartnersId' => 'nullable|integer',
            'legal_name'   => 'required|string|max:255',
            'logo_url'     => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'commission'   => 'nullable|numeric|min:0|max:100',
            'phone'        => 'required|digits_between:10,15',
            'email'        => 'nullable|email|max:255',
            'gst_number'   => 'nullable|string|size:15',
            'pan_number'   => 'nullable|string|size:10',
            'city'         => 'required|string|max:100',
            'pin_code'     => 'required|digits_between:4,10',
        ];
    }

    public function messages()
    {
        return [
            'legal_name.required'  => 'Channel Partners Name is required',
            'phone.required'       => 'Phone is required',
            'phone.digits_between' => 'Phone must be between 10 and 15 digits',
            'email.email'          => 'Email must be valid',
            'gst_number.size'      => 'GST must be exactly 15 characters',
            'pan_number.size'      => 'PAN must be exactly 10 characters',
            'city.required'        => 'City is required',
            'pin_code.required'    => 'PIN Code is required',
            'logo_url.mimes'       => 'Logo must be a file of type: jpg, jpeg, png, gif',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

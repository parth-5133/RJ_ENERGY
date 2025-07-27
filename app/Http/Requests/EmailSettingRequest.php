<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'nullable',
            'mail_driver' => 'required|string|max:50',
            'mail_host' => 'required|string|max:100',
            'mail_port' => 'required|numeric|min:1|max:65535',
            'mail_username' => 'required|string|max:100',
            'cc_mail_username' => 'nullable|email|max:100',
            'mail_password' => 'required|string|max:100',
            'mail_encryption' => 'nullable|string|in:tls,ssl,',
            'mail_from_address' => 'required|email|max:100',
            'mail_from_name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'mail_driver.required' => 'Mail driver is required',
            'mail_driver.string' => 'Mail driver must be a string',
            'mail_driver.max' => 'Mail driver cannot exceed 50 characters',

            'mail_host.required' => 'Mail host is required',
            'mail_host.string' => 'Mail host must be a string',
            'mail_host.max' => 'Mail host cannot exceed 100 characters',

            'mail_port.required' => 'Mail port is required',
            'mail_port.numeric' => 'Mail port must be a number',
            'mail_port.min' => 'Mail port must be at least 1',
            'mail_port.max' => 'Mail port cannot exceed 65535',

            'mail_username.required' => 'Mail username is required',
            'mail_username.string' => 'Mail username must be a string',
            'mail_username.max' => 'Mail username cannot exceed 100 characters',

            'cc_mail_username.email' => 'CC Mail Username must be a valid email',
            'cc_mail_username.max' => 'CC Mail Username cannot exceed 100 characters',

            'mail_password.required' => 'Mail password is required',
            'mail_password.string' => 'Mail password must be a string',
            'mail_password.max' => 'Mail password cannot exceed 100 characters',

            'mail_encryption.string' => 'Mail encryption must be a string',
            'mail_encryption.in' => 'Mail encryption must be tls, ssl, or empty',

            'mail_from_address.required' => 'From address is required',
            'mail_from_address.email' => 'From address must be a valid email',
            'mail_from_address.max' => 'From address cannot exceed 100 characters',

            'mail_from_name.required' => 'From name is required',
            'mail_from_name.string' => 'From name must be a string',
            'mail_from_name.max' => 'From name cannot exceed 100 characters',

            'is_active.boolean' => 'Active status must be true or false',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

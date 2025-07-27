<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('id'))->whereNull('deleted_at'),
            ],
            'password' => 'required|string|min:3|confirmed',
            'ip_address' => 'nullable|ip',
            'last_password_updated_at' => 'nullable|date',
            'last_logged_in_at' => 'nullable|date',
            'is_active' => 'boolean|nullable',
            'role_id' => 'nullable|exists:roles,id',
        ];

        // Make password nullable if the request has an ID (for updates)
        if ($this->input('userId')) {
            $rules['password'] = 'nullable|string|min:3|confirmed';
        }

        if ($this->input('userId')) {
            $rules['email'] = 'required|string|email|max:255';
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name must not exceed 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name must not exceed 255 characters.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email must not exceed 255 characters.',
            'email.unique' => 'This email address is already taken.',
            'password.required' => 'The password is required when creating a user.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 3 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role_id.exists' => 'The selected role ID is invalid.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

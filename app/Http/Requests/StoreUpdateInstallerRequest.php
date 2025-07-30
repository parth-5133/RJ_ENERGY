<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateInstallerRequest extends FormRequest
{
    public function rules(): array
    {
        $installerId = $this->input('installersId') ?? $this->route('installer')?->id;

        return [
            'installersId' => 'nullable|integer',
            'name' => 'required|string|max:50',
            'phone' => 'required|digits_between:10,15',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('installers', 'email')
                    ->ignore($installerId)
                    ->whereNull('deleted_at'),
            ],
            'address' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Installer name is required.',
            'name.max' => 'Installer name cannot be more than 50 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.digits_between' => 'Phone number must be between 10 and 15 digits.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'address.max' => 'Address cannot exceed 255 characters.',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

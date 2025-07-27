<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;

class StoreUpdateRoleRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = (int) GetCompanyId::GetCompanyId();

        $rules = [
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('roles', 'code')
                    ->ignore($this->input('roleId'))
                    ->where(function ($query) use ($CompanyId) {
                        return $query->where('company_id', $CompanyId)
                            ->whereNull('deleted_at');
                    }),
            ],
            'access_level' => 'required|numeric|max:100',
            'is_active' => 'nullable|boolean',
        ];

        if (!$this->input('roleId')) {
            $rules['code'] = [
                'required',
                'string',
                'max:10',
                Rule::unique('roles', 'code')
                    ->where(function ($query) use ($CompanyId) {
                        return $query->where('company_id', $CompanyId)
                            ->whereNull('deleted_at');
                    }),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required.',
            'name.string' => 'The role name must be a string.',
            'name.max' => 'The role name must not exceed 255 characters.',
            'code.required' => 'The role code is required for creating a new role.',
            'code.string' => 'The role code must be a string.',
            'code.max' => 'The role code must not exceed 10 characters.',
            'code.unique' => 'The role code has already been taken. Please choose a different code.',
            'access_level.required' => 'The access level is required.',
            'access_level.numeric' => 'The access level must be a number.',
            'access_level.max' => 'The access level must not exceed 100.',
            'is_active.boolean' => 'The active status must be either true or false.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

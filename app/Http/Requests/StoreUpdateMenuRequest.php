<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;

class StoreUpdateMenuRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->has('access_code')) {
            $this->merge([
                'access_code' => strtoupper($this->input('access_code')),
            ]);
        }
    }

    public function rules(): array
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = (int) GetCompanyId::GetCompanyId();

        $rules = [
            'name' => 'required|string|max:255',
            'access_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('menus', 'access_code')
                    ->ignore($this->route('id')) // use route param or input ID
                    ->where(function ($query) use ($CompanyId) {
                        return $query->where('company_id', $CompanyId)
                            ->whereNull('deleted_at');
                    }),
            ],
            'navigation_url' => 'nullable|string|max:255',
            'display_in_menu' => 'required|boolean',
            'parent_menu_id' => 'nullable|integer',
            'menu_icon' => 'nullable|string|max:255',
            'menu_class' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ];

        // For create (menuId not provided), set required + same company check
        if (!$this->input('menuId')) {
            $rules['access_code'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('menus', 'access_code')
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
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            'access_code.required' => 'The access code is required when creating a new menu.',
            'access_code.string' => 'The access code must be a string.',
            'access_code.max' => 'The access code must not exceed 50 characters.',
            'access_code.unique' => 'The access code must be unique.',
            'navigation_url.string' => 'The navigation URL must be a string.',
            'navigation_url.max' => 'The navigation URL must not exceed 255 characters.',
            'display_in_menu.required' => 'The display in menu field is required.',
            'display_in_menu.boolean' => 'The display in menu field must be true or false.',
            'parent_menu_id.integer' => 'The parent menu ID must be an integer.',
            'menu_icon.string' => 'The menu icon must be a string.',
            'menu_icon.max' => 'The menu icon must not exceed 255 characters.',
            'menu_class.string' => 'The menu class must be a string.',
            'menu_class.max' => 'The menu class must not exceed 255 characters.',
            'display_order.required' => 'The display order is required.',
            'display_order.integer' => 'The display order must be an integer.',
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

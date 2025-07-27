<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class RolePermissionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'permissions' => 'nullable|array',
            'permissions.*.menu_id' => 'required_with:permissions|integer|exists:menus,id',
        ];

    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }

}

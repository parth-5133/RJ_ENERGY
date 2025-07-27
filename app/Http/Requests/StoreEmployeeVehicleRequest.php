<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEmployeeVehicleRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'id' => 'nullable',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'driving_license_no' => 'required|string|max:255',
            'vehicle_number' => [
                'required',
                'string',
                'max:10',
                Rule::unique('employee_vehicles', 'vehicle_number')->ignore($this->input('id'))->whereNull('deleted_at')
            ],
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

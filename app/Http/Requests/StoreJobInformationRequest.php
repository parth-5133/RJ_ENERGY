<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreJobInformationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'job_title' => 'required|string|max:255',
            'department' => 'required|exists:departments,id',
            'location' => 'required|string|max:255',
            'employee_type' => 'required|exists:employee_types,id',
            'date_of_joining' => 'required|date',
            'employee_status' => 'required|exists:employee_statuses,id',
            'reporting_id' => 'required|exists:users,id',
            'designation' => 'required|string|max:255',
            'work_schedule' => 'required|string|max:255',
            'job_description' => 'nullable|string',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

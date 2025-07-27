<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class UpdateAttendanceRequest extends FormRequest
{
    public function rules(Request $requests)
    {
        $rules = [
            'id' => 'required',
            'attendance_date' => 'required|date',
            'attendance_time' => 'required',
            'attendance_status' => 'required',
            'request_status' => 'required',
            'comment' => 'nullable',
        ];

        if ($this->input('request_status') != 1) {
            $rules['comment'] = 'required';
        }

        return $rules;
    }


    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

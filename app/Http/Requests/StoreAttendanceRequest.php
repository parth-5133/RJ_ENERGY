<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAttendanceRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'session_type' => 'required',
            'action' => 'required|in:check_in,check_out,break_in,break_out',
            'note' => 'required_if:action,check_in',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

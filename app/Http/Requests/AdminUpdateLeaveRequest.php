<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminUpdateLeaveRequest extends FormRequest
{
    public function rules(Request $request)
    {
        return [
            'leave_status' => 'required|integer',
            'comment' => 'required_unless:leave_status,1|string|nullable',
        ];
    }

    // Override the messages method to provide custom messages
    public function messages()
    {
        return [
            'leave_status.required' => 'The leave status is mandatory.',
            'leave_status.integer' => 'The leave status must be an integer.',
            'comment.required_unless' => 'The comment is required.',
            'comment.string' => 'The comment must be a string.',
            'comment.nullable' => 'The comment may be null.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

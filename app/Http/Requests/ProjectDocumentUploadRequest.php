<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ProjectDocumentUploadRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'document' => 'required|max:10240', // max 10MB
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'Document is required.',
            'document.mimes' => 'Please upload a valid file (pdf, doc, docx, xls, xlsx, png, jpg, jpeg).',
            'document.max' => 'The file size should not exceed 10MB.',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

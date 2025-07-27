<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class StoreEmployeeDocumentRequest extends FormRequest
{
    public function rules(Request $request)
    {
        $rules = [
            'resume_cv' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048',
            'offer_letter' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048',
            'contracts' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048',
        ];

        if ($request->has('id') && !empty($request->input('id'))) {
            // If 'id' is present and not empty, make these fields nullable
            $rules['id_proofs'] = 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048';
            $rules['work_permits_visa'] = 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048';
        } else {
            // If 'id' is not present, make these fields required
            $rules['id_proofs'] = 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048';
            $rules['work_permits_visa'] = 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg,gif,webp|max:2048';
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

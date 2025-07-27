<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ProjectRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $id = $request->input('project_Id');

        return [
            'projectId'   => 'nullable',
            'project_name' => ['required', 'string', 'max:50', $id ? '' :  Rule::unique('projects', 'project_name')->ignore($id)->whereNull('deleted_at'),],
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'priority'       => 'required',
            'client'         => 'required|integer',
            'team_members'   => 'nullable|array',
            'team_members.*' => 'integer|exists:users,id',
            'team_leaders'   => 'nullable|array',
            'team_leaders.*' => 'integer|exists:users,id',
            'description'    => 'nullable|string',
            'document'       => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:2048',
            'is_active'      => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'project_name.required'   => 'The project name is required and must be a string with a maximum of 50 characters.',
            'project_name.unique'     => 'The project name has already been taken. Please choose a different name.',
            'start_date.required'     => 'The start date is required and must be a valid date.',
            'end_date.required'       => 'The end date is required and must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
            'priority.required'       => 'The project priority is required and must be one of the following values: low, medium, or high.',
            'client.required'         => 'The client is required and must be a valid client ID.',
            'team_members.array'      => 'Team members must be provided as an array of valid user IDs.',
            'team_members.*.integer'  => 'Each team member ID must be a valid integer.',
            'team_members.*.exists'   => 'Each team member must exist in the system.',
            'team_leaders.array'      => 'Team leaders must be provided as an array of valid user IDs.',
            'team_leaders.*.integer'  => 'Each team leader ID must be a valid integer.',
            'team_leaders.*.exists'   => 'Each team leader must exist in the system.',
            'document.file'           => 'The document must be a valid file.',
            'document.mimes'          => 'The document must be one of the following file types: pdf, doc, docx, xls, xlsx, png, jpg, jpeg.',
            'document.max'            => 'The document size must not exceed 2MB.',
            'is_active.boolean'       => 'The is_active field must be either true or false.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

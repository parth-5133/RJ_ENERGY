<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $id = $request->input('taskId');
        $roleCode = $request->input('roleCode');
        $taskStatusId = (int) $request->input('status');
        $projectId = (int) $request->input('project_id');

        // Default to null
        $statusName = null;

        if (!is_null($taskStatusId) && !is_null($projectId)) {
            $statusName = DB::table('kanban_columns')
                ->where('project_id', $projectId)
                ->where('id', $taskStatusId)
                ->value('column_name');
        }

        // cast to int for comparison

        $rules = [
            'taskId' => 'nullable|integer',
            'priority' => 'nullable|integer|between:1,5',
            'team_members' => 'nullable',
            'team_members.*' => 'integer|exists:users,id',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:2048',
        ];

        if ($taskStatusId === 0) {
            $rules['start_time'] = 'nullable|before:end_time';
            $rules['end_time'] = 'nullable';
        } elseif ($statusName === 'To Do') {
            $rules['start_time'] = 'nullable|before:end_time';
            $rules['end_time'] = 'nullable';
        } else {
            $rules['start_time'] = 'required|before:end_time';
            $rules['end_time'] = 'required';
        }

        if ($roleCode === $this->superAdminRoleCode || $roleCode === $this->AdminRoleCode) {
            $rules['title'] = 'required|string|max:50';
            $rules['due_date'] = ['required', 'date', $id ? '' : 'after_or_equal:today'];
            $rules['priority'] = 'required|integer|between:1,5';
            $rules['project_id'] = 'required|integer|exists:projects,id';
            $rules['status'] = 'nullable';
        } else {
            $rules['due_date'] = ['nullable', 'date', $id ? '' : 'after_or_equal:today'];
            $rules['status'] = 'nullable';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Task title is required.',
            'title.string' => 'Task title must be a string.',
            'title.max' => 'Task title cannot exceed 50 characters.',

            'due_date.required' => 'Due date is required.',
            'due_date.date' => 'Due date must be a valid date.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',

            'priority.required' => 'Priority is required.',
            'priority.integer' => 'Priority must be a number.',
            'priority.between' => 'Priority must be between 1 and 5.',

            'status.required' => 'Status is required.',

            'project_id.required' => 'Project is required.',
            'project_id.integer' => 'Project must be a valid project ID.',
            'project_id.exists' => 'Selected project does not exist.',

            'team_members.*.integer' => 'Each team member ID must be a valid number.',
            'team_members.*.exists' => 'Each team member must be a valid user ID.',

            'description.string' => 'Description must be a string.',

            'document.file' => 'Document must be a valid file.',
            'document.mimes' => 'Document must be a file of type: pdf, doc, docx, xls, xlsx, png, jpg, jpeg.',
            'document.max' => 'Document size cannot exceed 2MB.',

            'start_time.required' => 'Start time is required.',
            'start_time.before' => 'Start time must be before end time.',

            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

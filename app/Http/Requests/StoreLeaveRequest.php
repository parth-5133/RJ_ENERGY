<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Helpers\JWTUtils;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class StoreLeaveRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $leaveId = $this->input('LeaveId') ?? null;
        $startDate = Carbon::parse($this->input('start_date'));
        $endDate = Carbon::parse($this->input('end_date'));

        return [
            'LeaveId' => 'nullable',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_session_id' => 'required',
            'start_date' => [
                'required',
                'date',
                Rule::unique('leave_requests', 'start_date')
                    ->where(function ($query) use ($currentUser) {
                        return $query->where('employee_id', $currentUser->id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($leaveId), // Ignore the current leave request when updating
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                Rule::when($this->input('leave_session_id') != 1, ['same:start_date']),
                Rule::unique('leave_requests', 'end_date')
                    ->where(function ($query) use ($currentUser) {
                        return $query->where('employee_id', $currentUser->id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($leaveId), // Ignore the current leave request when updating
                function ($attribute, $value, $fail) use ($startDate, $endDate, $currentUser, $leaveId) {
                    // Check for overlapping leave requests
                    $overlappingLeave = LeaveRequest::where('employee_id', $currentUser->id)
                        ->whereNull('deleted_at')
                        ->where(function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate])
                                ->orWhere(function ($query) use ($startDate, $endDate) {
                                    $query->where('start_date', '<=', $startDate)
                                        ->where('end_date', '>=', $endDate);
                                });
                        })
                        ->when($leaveId, function ($query) use ($leaveId) {
                            return $query->where('id', '!=', $leaveId);
                        })
                        ->exists();

                    if ($overlappingLeave) {
                        $fail('You already have leave scheduled during the selected date range.');
                    }
                },
            ],
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'required|string|max:255',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

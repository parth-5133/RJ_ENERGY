<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\EmployeeAttendance;
use App\Models\AttendanceRequest;
use App\Helpers\JWTUtils;
use Illuminate\Http\Request;

class AttendanceRequestStore extends FormRequest
{
    public function rules(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $userId = $request->get('userId');

        $skipValidation = $userId > 0;

        return [
            'attendance_status' => 'required|in:check_in,check_out',
            'attendance_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($currentUser, $skipValidation) {
                    if ($skipValidation) {
                        return; // Skip this validation if userId > 0 (update)
                    }

                    // Validation for creating a new record (userId == 0)
                    $existingRecord = AttendanceRequest::where('attendance_date', $value)
                        ->where('attendance_status', $this->attendance_status)
                        ->where('employee_id', $currentUser->id)
                        ->first();

                    if ($existingRecord) {
                        $fail('You have already submitted a ' . $this->attendance_status . ' for this date.');
                    }
                },
            ],
            'attendance_time' => [
                'required',
                function ($attribute, $value, $fail) use ($currentUser, $skipValidation) {
                    if ($skipValidation) {
                        return; // Skip this validation if userId > 0 (update)
                    }

                    // Validation for check-out time (creating a new record)
                    if ($this->attendance_status === 'check_out') {
                        $existingCheckIn = EmployeeAttendance::where('date', $this->attendance_date)
                            ->where('employee_id', $currentUser->id)
                            ->whereNotNull('check_in_time')
                            ->first();

                        if ($existingCheckIn) {
                            $checkInTime = $existingCheckIn->check_in_time;
                            if ($value <= $checkInTime) {
                                $fail('The check-out time must be greater than the check-in time.');
                            }
                        }
                    }
                },
            ],
            'note' => 'required|max:255',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation errors', $validator->errors())
        );
    }
}

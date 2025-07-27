<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Http\Requests\StoreLeaveRequest;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendToAdmin;
use App\Mail\SendToEmployee;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Role;
use App\Helpers\NotificationHelper;


class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $leaveRequests = DB::table('leave_requests')
            ->leftJoin('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('users', 'leave_requests.approved_by', '=', 'users.id')
            ->select(
                'leave_requests.id',
                'leave_types.leave_type_name as leave_type',
                'leave_requests.start_date',
                'leave_requests.end_date',
                'leave_requests.total_days',
                'leave_requests.reason',
                'leave_requests.status',
                'leave_requests.comments',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as approved_by_name")
            )
            ->where('leave_requests.employee_id', $currentUser->id)
            ->whereNull('leave_requests.deleted_at')
            ->orderByDesc('id')
            ->get();
        $data = $leaveRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'leave_type' => $request->leave_type,
                'start_date' =>  Carbon::parse($request->start_date)->format('d/m/Y'),
                'end_date' => Carbon::parse($request->end_date)->format('d/m/Y'),
                'total_days' => $request->total_days,
                'reason' => $request->reason,
                'status' => $request->status,
                'approved_by' => $request->approved_by_name ?? null,
                'comments' => $request->comments ?? null,
            ];
        });

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchAllLeaveType(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $data = LeaveType::where('is_currentYear', true)
            ->where('company_id', $CompanyId)
            ->get();

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreLeaveRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $validatedData = $request->validated();

        $lossOfPayLeaveType = LeaveType::where('leave_type_name', 'Leave without Pay')->first();
        $id = $lossOfPayLeaveType->id;

        $leaveBalance = null;

        if ($validatedData['leave_type_id'] != $id) {
            // Only check balance for leave types other than "Leave without Pay"
            $leaveBalance = LeaveBalance::where('employee_id', $currentUser->id)
                ->where('leave_type_id', $validatedData['leave_type_id'])
                ->first();

            $totalAvailableDays = ($leaveBalance->balance ?? 0) + ($leaveBalance->carry_forwarded ?? 0);

            if (!$leaveBalance || $totalAvailableDays < $validatedData['total_days']) {
                return ApiResponse::error(null, 'You do not have enough leave balance to apply for this leave.');
            }
        }

        $holidays = DB::table('holidays')
            ->whereBetween('holiday_date', [$validatedData['start_date'], $validatedData['end_date']])
            ->whereNull('deleted_at')
            ->pluck('holiday_date')
            ->toArray();

        $holidayCount = 0;
        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (in_array($date->toDateString(), $holidays)) {
                $holidayCount++;
            }
        }

        $leaveDaysAfterHoliday = $validatedData['total_days'] - $holidayCount;

        $leaveRequest = LeaveRequest::create([
            'company_id' => $CompanyId,
            'employee_id' => $currentUser->id,
            'leave_type_id' => $validatedData['leave_type_id'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'leave_session_id' => $validatedData['leave_session_id'],
            'total_days' => $leaveDaysAfterHoliday,
            'reason' => $validatedData['reason'],
            'status' => 'pending',
            'request_date' => now(),
            'created_at' => now(),
            'updated_at' => null,
            'created_by' => $currentUser->id,
        ]);

        if ($leaveBalance) {
            $daysToDeduct = $leaveDaysAfterHoliday;

            if ($leaveBalance->carry_forwarded >= $daysToDeduct) {
                $leaveBalance->carry_forwarded -= $daysToDeduct;
            } else {
                $daysToDeduct -= $leaveBalance->carry_forwarded;
                $leaveBalance->carry_forwarded = 0;

                $leaveBalance->balance -= $daysToDeduct;
            }

            $leaveBalance->save();
        }

        if ($validatedData['leave_type_id'] == $id) {
            $leaveBalance = LeaveBalance::where('employee_id', $currentUser->id)
                ->where('leave_type_id', $validatedData['leave_type_id'])
                ->first();

            $leaveBalance->balance += $leaveDaysAfterHoliday;
            $leaveBalance->save();
        }

        $notify = NotificationHelper::canSendNotification('leave_request');

        if ($leaveRequest->save()) {

            if ($notify['browser']) {

                $template = NotificationTemplate::where('template_name', 'leave_request_created')->first();

                $leaveTypeName = LeaveType::where('id', $validatedData['leave_type_id'])->value('leave_type_name');

                $title = str_replace(
                    ['{employee_name}'],
                    [$currentUser->first_name],
                    $template->title
                );

                $message = str_replace(
                    ['{employee_name}', '{leave_type_name}'],
                    [$currentUser->first_name, $leaveTypeName],
                    $template->message
                );

                $userIds = Role::where('code', $this->AdminRoleCode)
                    ->where('roles.company_id', $CompanyId)
                    ->whereNull('roles.deleted_at')
                    ->where('roles.is_active', true)
                    ->leftJoin('users', 'roles.id', '=', 'users.role_id')
                    ->whereNull('users.deleted_at')
                    ->pluck('users.id');

                foreach ($userIds as $userId) {
                    Notification::create([
                        'company_id' => $CompanyId,
                        'user_id' => $userId,
                        'title' => $title,
                        'message' => $message,
                        'has_view_button' => false,
                        'created_at' => now(),
                        'created_by' => $currentUser->id,
                    ]);
                }
            }
        }

        if ($notify['email']) {

            $adminEmail = env('MAIL_USERNAME');
            $employeeEmail = auth()->user()->email;

            try {
                Mail::to($adminEmail)->send(new SendToAdmin($leaveRequest));
                Mail::to($employeeEmail)->send(new SendToEmployee($leaveRequest));
            } catch (\Exception $e) {
                return ApiResponse::error(null, 'Your leave request has been sent successfully, but failed to send email notifications. Please contact support.');
            }
        }

        return ApiResponse::success($leaveRequest, ResMessages::RETRIEVED_SUCCESS);
    }
    public function view(Request $request)
    {
        $LeaveId = $request->LeaveId;

        $data = DB::table('leave_requests')
            ->leftJoin('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->select(
                'leave_requests.*',
                'leave_types.id as leave_type_id',
                'leave_types.leave_type_name as leave_type_name'
            )
            ->where('leave_requests.id', $LeaveId)
            ->first();


        $employeeData = User::where('id', $data->employee_id)
            ->select('employee_id')
            ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
            ->first();
        $data = [
            'leave_request' => $data,
            'employee' => $employeeData,
        ];

        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreLeaveRequest $request)
    {
        $LeaveId = $request->LeaveId;
        $validatedData = $request->validated();

        // Find the leave request by ID
        $leaveRequest = LeaveRequest::find($LeaveId);

        if (!$leaveRequest) {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }

        $oldTotalDays = $leaveRequest->total_days;
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $employeeId = $currentUser->id;
        $leaveTypeId = $request->input('leave_type_id');
        $newTotalDays = $request->input('total_days');

        // Fetch the leave type details
        $leaveType = LeaveType::where('id', $leaveTypeId)
            ->select('max_days', 'carry_forward_max_balance', 'leave_type_name')
            ->first();

        $id = $leaveType->id;

        // Check if the total days are being increased
        if ($oldTotalDays < $validatedData['total_days']) {
            if ($validatedData['leave_type_id'] != $id) {
                // Only check balance for leave types other than "Leave without Pay"
                $leaveBalance = LeaveBalance::where('employee_id', $currentUser->id)
                    ->where('leave_type_id', $validatedData['leave_type_id'])
                    ->first();

                $totalAvailableDays = ($leaveBalance->balance ?? 0) + ($leaveBalance->carry_forwarded ?? 0);

                if (!$leaveBalance || $totalAvailableDays < $validatedData['total_days']) {
                    return ApiResponse::error(null, 'You do not have enough leave balance to apply for this leave.');
                }
            }
        }

        // Fetch holidays within the date range that are not soft deleted
        $holidays = DB::table('holidays')
            ->whereBetween('holiday_date', [$validatedData['start_date'], $validatedData['end_date']])
            ->whereNull('deleted_at') // Ensure only non-deleted holidays are considered
            ->pluck('holiday_date')
            ->toArray();

        // Calculate the number of holiday days
        $holidayCount = 0;
        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);

        // Loop through the date range to count holidays
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (in_array($date->toDateString(), $holidays)) {
                $holidayCount++;
            }
        }

        // Adjust the total days by subtracting holidays
        $leaveDaysAfterHoliday = $validatedData['total_days'] - $holidayCount;

        // Update the leave request with the new data
        $leaveRequest->update([
            'leave_type_id' => $leaveTypeId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'leave_session_id' => $request->input('leave_session_id'),
            'total_days' => $leaveDaysAfterHoliday, // Set the adjusted total days after removing holidays
            'reason' => $request->input('reason'),
        ]);

        // Handle balance deduction and carry forward for the leave type
        $lossOfPayLeaveType = LeaveType::where('leave_type_name', 'Leave without Pay')->first();
        $lossOfPayId = $lossOfPayLeaveType ? $lossOfPayLeaveType->id : null;

        // Handle the balance updates only for leave types other than "Leave without Pay"
        if ($leaveTypeId != $lossOfPayId) {
            $leaveBalance = LeaveBalance::where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveTypeId)
                ->first();

            // Calculate the difference between new total days and old total days
            $difference = $leaveDaysAfterHoliday - $oldTotalDays;

            if ($difference > 0) {
                // Deduct leave balance if days are increased
                $remainingDaysToDeduct = $difference;

                if ($leaveBalance->carry_forwarded >= $remainingDaysToDeduct) {
                    $leaveBalance->carry_forwarded -= $remainingDaysToDeduct;
                    $remainingDaysToDeduct = 0;
                } else {
                    $remainingDaysToDeduct -= $leaveBalance->carry_forwarded;
                    $leaveBalance->carry_forwarded = 0;
                }

                if ($remainingDaysToDeduct > 0) {
                    if ($leaveBalance->balance >= $remainingDaysToDeduct) {
                        $leaveBalance->balance -= $remainingDaysToDeduct;
                        $remainingDaysToDeduct = 0;
                    }
                }
            } elseif ($difference < 0) {
                // Add leave balance if days are decreased
                $daysToAdd = abs($difference);
                $remainingDaysToAdd = $daysToAdd;

                $availableBalanceSpace = $leaveType->max_days - $leaveBalance->balance;

                if ($availableBalanceSpace > 0) {
                    $daysAddedToBalance = min($remainingDaysToAdd, $availableBalanceSpace);
                    $leaveBalance->balance += $daysAddedToBalance;
                    $remainingDaysToAdd -= $daysAddedToBalance;
                }

                if ($remainingDaysToAdd > 0) {
                    $availableCarryForwardSpace = $leaveType->carry_forward_max_balance - $leaveBalance->carry_forwarded;

                    if ($availableCarryForwardSpace > 0) {
                        $daysAddedToCarryForward = min($remainingDaysToAdd, $availableCarryForwardSpace);
                        $leaveBalance->carry_forwarded += $daysAddedToCarryForward;
                        $remainingDaysToAdd -= $daysAddedToCarryForward;
                    }
                }
            }

            // Save the updated leave balance
            $leaveBalance->save();
        }

        // Return the updated leave request
        return ApiResponse::success($leaveRequest, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $data = LeaveRequest::find($id);

        if (!$data) {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }

        $employeeId = $data->employee_id;
        $leaveTypeId = $data->leave_type_id;
        $totalDay = $data->total_days;

        $leaveBalance = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->first();

        $leaveType = LeaveType::where('id', $leaveTypeId)
            ->select('max_days', 'carry_forward_max_balance')
            ->first();

        if ($leaveBalance && $leaveType) {
            $remainingDays = $totalDay;

            $maxDays = $leaveType->max_days;
            $carryForwardMaxBalance = $leaveType->carry_forward_max_balance;

            $availableBalanceSpace = $maxDays - $leaveBalance->balance;

            if ($remainingDays <= $availableBalanceSpace) {
                $leaveBalance->balance += $remainingDays;
                $remainingDays = 0;
            } else {
                $leaveBalance->balance = $maxDays;
                $remainingDays -= $availableBalanceSpace;
            }

            if ($remainingDays > 0) {
                $availableCarryForwardSpace = $carryForwardMaxBalance - $leaveBalance->carry_forwarded;

                if ($remainingDays <= $availableCarryForwardSpace) {
                    $leaveBalance->carry_forwarded += $remainingDays;
                } else {
                    $leaveBalance->carry_forwarded = $carryForwardMaxBalance;
                }
            }

            $leaveBalance->save();
        }

        $data->delete();

        return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
    }
}

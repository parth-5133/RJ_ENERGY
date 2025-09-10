<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Helpers\JWTUtils;
use App\Http\Requests\AdminUpdateLeaveRequest;
use App\Helpers\AccessLevel;
use App\Helpers\GetCompanyId;
use App\Models\LeaveType;
use App\Models\User;
use App\Http\Requests\StoreLeaveTypeRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRequestApproved;
use App\Enums\LeaveStatus;
use App\Helpers\GetYear;
use App\Helpers\FinancialYearService;

class AdminLeaveController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $leaveStatus = $request->query('leave_status');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        // Initialize the query
        $leavesQuery = DB::table('leave_requests')
            ->select(
                'leave_requests.id',
                DB::raw("CONCAT(employee.first_name, ' ', employee.last_name) as employee_name"),
                'leave_types.leave_type_name',
                'leave_requests.start_date',
                'leave_requests.end_date',
                'leave_requests.leave_session_id',
                'leave_requests.total_days',
                'leave_requests.reason',
                'leave_requests.status',
                'leave_requests.updated_by',
                DB::raw("DATE_FORMAT(leave_requests.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
            )
            ->join('users as employee', 'leave_requests.employee_id', '=', 'employee.id')
            ->leftJoin('users as updater', 'leave_requests.updated_by', '=', 'updater.id')
            ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->whereNull('leave_requests.deleted_at')
            ->whereIn('leave_requests.status', ['Approved', 'Rejected', 'Cancelled', 'Pending']);

        // Filter by company_id if exists
        if ($companiesId) {
            $leavesQuery->where('leave_requests.company_id', $companiesId);
        }

        // Filter by employee_id if provided
        if ($employeeId) {
            $leavesQuery->where('leave_requests.employee_id', $employeeId);
        }

        // Handle leave status filtering
        if ($leaveStatus === "0") {
            // Show all leave requests, do not filter by status
        } elseif ($leaveStatus !== null && is_numeric($leaveStatus)) {
            $leaveStatusLabel = LeaveStatus::from((int)$leaveStatus)->label();
            $leavesQuery->where('leave_requests.status', $leaveStatusLabel);
        } else {
            // If no leave status is provided, filter by all possible statuses from the enum.
            $leaveStatusLabels = array_map(fn($status) => $status->label(), LeaveStatus::cases());
            $leavesQuery->whereIn('leave_requests.status', $leaveStatusLabels);
        }

        // Filter by date range if provided
        if ($fromDate) {
            $leavesQuery->where('leave_requests.start_date', '>=', $fromDate);
        }

        if ($toDate) {
            $leavesQuery->where('leave_requests.end_date', '<=', $toDate);
        }

        // Get the filtered results
        $leaves = $leavesQuery->get();

        // Map the results for response
        $leaves = $leaves->map(function ($leave) {
            return [
                'id' => $leave->id,
                'employee_name' => $leave->employee_name,
                'leave_type_name' => $leave->leave_type_name,
                'start_date' => Carbon::parse($leave->start_date)->format('d/m/Y'),
                'end_date' => Carbon::parse($leave->end_date)->format('d/m/Y'),
                'leave_session_id' => $leave->leave_session_id,
                'total_days' => $leave->total_days,
                'reason' => $leave->reason,
                'status' => $leave->status,
                'updated_name' => $leave->updated_name ?? '', // Default to 'N/A' if null
                'updated_at_formatted' => $leave->updated_at_formatted ?? '', // Default to 'N/A' if null
            ];
        });

        return ApiResponse::success($leaves, ResMessages::RETRIEVED_SUCCESS);
    }
    public function headerData(Request $request)
    {
        $leaveRequests = DB::table('leave_requests')
            ->whereNull('deleted_at')
            ->get();

        $pendingLeaveRequests = $leaveRequests->filter(function ($item) {
            return $item->status === 'Pending';
        })->count();

        $approvedLeaveRequests = $leaveRequests->filter(function ($item) {
            return $item->status === 'Approved';
        })->count();

        $today = Carbon::today();

        $plannedLeaves = $leaveRequests->filter(function ($item) use ($today) {
            return $item->status === 'Approved' &&
                Carbon::parse($item->start_date)->lessThanOrEqualTo($today) &&
                Carbon::parse($item->end_date)->greaterThanOrEqualTo($today);
        })->count();

        $presentEmployeesCount = DB::table('employee_attendances')
            ->whereDate('date', Carbon::today())
            ->distinct('employee_id')             // Ensure unique employee IDs
            ->count('employee_id');

        $currentAccessLevel = AccessLevel::getAccessLevel();

        $employees = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name"))
            ->where('roles.access_level', '<', $currentAccessLevel->access_level)
            ->where('roles.code',  $this->employeeRoleCode)
            ->whereNull('users.deleted_at')
            ->get();

        $totalEmployees = $employees->count();

        $data = [
            'pendingLeaveRequests' => $pendingLeaveRequests,
            'approvedLeaveRequests' => $approvedLeaveRequests,
            'plannedLeaves' => $plannedLeaves,
            'presentEmployeesCount' => $presentEmployeesCount,
            'users' => $employees,
            'totalEmployees' => $totalEmployees,
        ];
        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function update(AdminUpdateLeaveRequest $request)
    {
        $LeaveId = $request->LeaveId;

        $leaveRequest = LeaveRequest::find($LeaveId);

        if (is_null($leaveRequest)) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $totalDays = $leaveRequest->total_days;
        $employeeId = $leaveRequest->employee_id;
        $leaveTypeId = $leaveRequest->leave_type_id;
        $Status = $request->leave_status;

        if (!empty($totalDays) && $Status == 2) {

            DB::table('leave_balances')
                ->where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveTypeId)
                ->increment('balance', $totalDays);
        }

        $employee_id = $leaveRequest->employee_id;
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $leaveRequest->status = $request->leave_status;
        $leaveRequest->comments  = $request->comment;
        $leaveRequest->approved_by  = $currentUser->id;
        $leaveRequest->approval_date  = now();
        $leaveRequest->updated_at  = now();
        $leaveRequest->updated_by   = $currentUser->id;

        $leaveRequest->save();

        $employeeEmail = DB::table('users')
            ->where('id', $employee_id)
            ->value('email');

        if ($employeeEmail) {
            try {
                Mail::to($employeeEmail)->send(new LeaveRequestApproved($leaveRequest));
            } catch (\Exception $e) {
                return ApiResponse::error(null, 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
            }
        } else {
            return ApiResponse::error(null, 'User email not found');
        }

        return ApiResponse::success($leaveRequest,  ResMessages::UPDATED_SUCCESS);
    }
    public function deleteLeaveRequest($id)
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
    public function leaveSetting(Request $request)
    {
        $financialYears = GetYear::getYear();
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $leavesTypeQuery = DB::table('leave_types')
            ->leftJoin('users', 'leave_types.updated_by', '=', 'users.id')
            ->select(
                'leave_types.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(leave_types.updated_at, '%d/%m/%Y') as updated_at_formatted")
            );

        if ($companiesId) {
            $leavesTypeQuery->where('leave_types.company_id', $companiesId);
        }

        if ($financialYears && isset($financialYears->id)) {
            $leavesTypeQuery->where('leave_types.financialYear_id', $financialYears->id);
        }

        $leavesType = $leavesTypeQuery->orderBy('leave_types.leave_type_name', 'asc')->get();

        return ApiResponse::success($leavesType, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createLeaveType(StoreLeaveTypeRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $financialYears = FinancialYearService::getCurrentFinancialYear();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $data = $request->all();
        $data['created_by'] = $currentUser->id;
        $data['created_at'] = now();
        $data['updated_at'] = null;
        $data['financialYear_id'] = $financialYears->id;
        $data['company_id'] = $CompanyId;

        $leaveType = LeaveType::create($data);

        if ($request->is_currentYear === true) {

            $users = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.company_id', $leaveType->company_id)
                ->whereNotIn('roles.code', [$this->AdminRoleCode, $this->superAdminRoleCode, $this->clientRoleCode])
                ->whereNull('users.deleted_at')
                ->select('users.*')
                ->get();

            $financialYears = FinancialYearService::getCurrentFinancialYear();
            foreach ($users as $user) {
                LeaveBalance::create([
                    'employee_id' => $user->id,
                    'company_id' => $leaveType->company_id,
                    'leave_type_id' => $leaveType->id,
                    'financialYear_id' => $financialYears->id,
                    'balance' => $leaveType->max_days,
                    'created_at' => now(),
                    'created_by' => $currentUser->id,
                ]);
            }
        }

        return ApiResponse::success($leaveType, ResMessages::CREATED_SUCCESS);
    }
    public function viewLeaveType(Request $request)
    {
        $leaveTypeId = $request->leaveTypeId;

        $data = LeaveType::find($leaveTypeId);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function updateLeaveType(StoreLeaveTypeRequest $request)
    {
        $financialYears = FinancialYearService::getCurrentFinancialYear();

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $leaveTypeId = $request->leaveTypeId;

        $data = LeaveType::find($leaveTypeId);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        // Fill the data and save
        $data->fill($request->validated());
        $data->updated_by = $currentUser->id;
        $data->updated_at = now();
        $data->save();

        $employees = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.company_id', $data->company_id)
            ->whereNotIn('roles.code', [$this->AdminRoleCode, $this->superAdminRoleCode, $this->clientRoleCode])
            ->whereNull('users.deleted_at')
            ->select('users.*')
            ->get();

        $employeeIds = $employees->pluck('id')->toArray();

        foreach ($employeeIds as $employeeId) {

            $currentBalance = DB::table('leave_balances')
                ->where('employee_id', $employeeId)
                ->where('financialYear_id', $financialYears->id)
                ->where('leave_type_id', $leaveTypeId)
                ->where('company_id', $data->company_id)
                ->value('balance');

            if ($currentBalance !== null) {
                $difference = $request->old_max_days - $currentBalance;

                $newBalance =  $request->max_days - $difference;

                DB::table('leave_balances')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type_id', $leaveTypeId)
                    ->where('financialYear_id', $financialYears->id)
                    ->where('company_id', $data->company_id)
                    ->update(['balance' => $newBalance]);
            } else {

                $Data = [
                    'employee_id' => $employeeId,
                    'leave_type_id' => $leaveTypeId,
                    'company_id' => $data->company_id,
                    'financialYear_id' => $financialYears->id,
                    'balance' => $request->input('max_days', 0),
                    'carry_forwarded' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'updated_by' => $currentUser->id,
                    'created_by' => $currentUser->id,
                ];

                LeaveBalance::create($Data);
            }
        }

        if ($request->is_currentYear === false) {
            LeaveBalance::where('leave_type_id', $leaveTypeId)->delete();
        }

        return ApiResponse::success($data, ResMessages::UPDATED_SUCCESS);
    }
    public function deleteLeaveType($id)
    {
        $leaveType = LeaveType::find($id);

        if ($leaveType) {

            LeaveBalance::where('leave_type_id', $id)->delete();

            $leaveType->delete();

            return ApiResponse::success(null, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
    public function getAllLeaveData(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $financialYears = GetYear::getYear();
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if (!$financialYears || !isset($financialYears->id)) {
            return ApiResponse::error('Financial year not found', 404);
        }

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $total_leaves = DB::table('leave_types')
            ->where('is_currentYear', 1)
            ->where('financialYear_id', $financialYears->id)
            ->where('company_id', $companiesId)
            ->sum('max_days');

        $leaveDataQuery = DB::table('leave_balances')
            ->join('users', 'leave_balances.employee_id', '=', 'users.id')
            ->leftJoin('users as updated_users', 'leave_balances.updated_by', '=', 'updated_users.id')
            ->select(
                'leave_balances.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                DB::raw('SUM(balance + carry_forwarded) as remaining_leave_balance'),
                DB::raw("DATE_FORMAT(COALESCE(MAX(leave_balances.updated_at), NOW()), '%d/%m/%Y') as updated_at_formatted"),
                DB::raw("CONCAT(COALESCE(MAX(updated_users.first_name), ''), ' ', COALESCE(MAX(updated_users.last_name), '')) as updated_name")
            )
            ->where('leave_balances.financialYear_id', '=', $financialYears->id)
            ->groupBy('leave_balances.employee_id', 'users.first_name', 'users.last_name');

        if ($companiesId) {
            $leaveDataQuery->where('leave_balances.company_id', $companiesId);
        }

        if ($employeeId && $employeeId != '0') {
            $leaveDataQuery->where('leave_balances.employee_id', $employeeId);
        }

        $leaveDataQuery->orderByRaw('MAX(leave_balances.updated_at) DESC');

        $leaveData = $leaveDataQuery->get();

        $data = $leaveData->map(function ($item) use ($total_leaves) {
            return [
                'employee_id' => $item->employee_id,
                'employee_name' => $item->employee_name,
                'remaining_leave_balance' => $item->remaining_leave_balance,
                'updated_at_formatted' => $item->updated_at_formatted,
                'updated_name' => $item->updated_name ?? '', // Fallback to 'N/A' if updated_name is null
                'total_days' => $total_leaves,
            ];
        });
        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function getLeavesReportData(Request $request)
    {
        $userId = $request->query('userId');
        $financialYears = GetYear::getYear();
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $user = DB::table('users')
            ->select('id', 'employee_id', 'first_name', 'last_name')
            ->addSelect(DB::raw('CONCAT(first_name, " ", last_name) as full_name'))
            ->where('id', $userId)
            ->where('role_id', '!=', 1)
            ->where('company_id', $companiesId)
            ->whereNull('deleted_at')
            ->first();

        $leaveBalances = DB::table('leave_balances')
            ->join('leave_types', 'leave_balances.leave_type_id', '=', 'leave_types.id')
            ->select('leave_balances.*', 'leave_types.leave_type_name as leave_type_name')
            ->where('leave_balances.employee_id', $userId)
            ->where('leave_balances.financialYear_id', '=', $financialYears->id)
            ->where('leave_balances.company_id', $companiesId)
            ->get();

        $responseData = [
            'user' => $user,
            'leaveBalances' => $leaveBalances,
        ];

        return ApiResponse::success($responseData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function updateLeavesReportData(Request $request)
    {
        $userId = $request->input('userId');
        $leaveBalances = $request->input('leaveBalances');
        $financialYears = GetYear::getYear();
        $currentUser = JWTUtils::getCurrentUserByUuid();

        foreach ($leaveBalances as $leaveTypeId => $balance) {
            DB::table('leave_balances')->updateOrInsert(
                [
                    'employee_id' => $userId,
                    'leave_type_id' => $leaveTypeId,
                    'financialYear_id' => $financialYears->id,
                ],
                [
                    'balance' => $balance,
                    'updated_at' => now(),
                    'updated_by' => $currentUser->id,
                ]
            );
        }

        return ApiResponse::success(null, ResMessages::UPDATED_SUCCESS);
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function getDepartmentEmployeeCount(Request $request)
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        // Join departments and employee_jobs
        $data = DB::table('departments as d')
            ->leftJoin('employee_jobs as ej', function ($join) use ($CompanyId) {
                $join->on('d.id', '=', 'ej.department')
                    ->whereNull('ej.deleted_at');
            })
            ->select(
                'd.name as department_name',
                DB::raw('COUNT(ej.user_id) as employee_count')
            )
            ->where('d.is_active', 1)
            ->whereNull('d.deleted_at')
            ->where('d.company_id', $CompanyId)
            ->groupBy('d.name')
            ->get();

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function getEmployeeAttendanceOverview(Request $request)
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $totalEmployee = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', 'users.role_id')
            ->where('users.company_id', $CompanyId)
            ->where('roles.code', '=', $this->employeeRoleCode)
            ->where('users.deleted_at', null)
            ->count();

        $data = DB::table('leave_requests')
            ->where('status', 'Approved')
            ->where('company_id', 1)
            ->whereNull('deleted_at')
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->select('id')
            ->get();

        $leaveCount = count($data);

        $attendanceCount = DB::table('employee_attendances')
            ->where('company_id', $CompanyId)
            ->where('date', Carbon::today())
            ->where('session_type', 'regular')
            ->count();

        $data = [
            'leave_count' => $leaveCount,
            'attendance_count' => $attendanceCount,
            'total_employees' => $totalEmployee,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $request->merge(['AccessCode' => config('menuAccessCode.ATTENDANCEREQUEST')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return $role_code === $this->superAdminRoleCode || $role_code === $this->clientRoleCode || $role_code === $this->AdminRoleCode ? view('admin.attendance.attendance_request_list', ['permissions' => $permissions, 'menuName' => $menuName]) :  view('employee.attendancrequest_list', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function attendanceReport(Request $request)
    {

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $request->merge(['AccessCode' => config('menuAccessCode.ATTENDANCEREPORT')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return $role_code === $this->superAdminRoleCode || $role_code === $this->clientRoleCode || $role_code === $this->AdminRoleCode ? view('admin.attendance.attendance_list', ['permissions' => $permissions, 'menuName' => $menuName]) :  view('employee.attendancelist', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public  function adminAttendanceRequest()
    {
        return view('admin.attendance.attendance_request_list');
    }

    public  function adminAttendanceRequestEdit(Request $request)
    {
        $employee_id = $request->input('id');
        return view('admin.attendance.attendance_request_edit', compact('employee_id'));
    }

    public function requestAttendance(Request $request)
    {
        $userId = $request->input('id');
        return view('employee.attendancerequest', compact('userId'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $request->merge(['AccessCode' => config('menuAccessCode.LEAVESREQUEST')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return $role_code === $this->superAdminRoleCode || $role_code === $this->AdminRoleCode ? view('admin.leave.leave', ['permissions' => $permissions, 'menuName' => $menuName]) :  view('employee.leaves', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function leaveReportData(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.LEAVESREPORT')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('admin.leave.leave_report', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function leaveSetting(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.LEAVESSETTINGS')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('admin.leave.leave_settings', ['permissions' => $permissions, 'menuName' => $menuName]);
    }


    public function employeeLeavesRequest(Request $request)
    {
        $LeaveId = $request->input('id');
        return view('employee.leavesRequest', compact('LeaveId'));
    }

    public function adminLeaveReport(Request $request)
    {
        $userId = $request->input('id');

        return view('admin.leave.leave_report_create', compact('userId'));
    }

    public function adminLeaveRequest(Request $request)
    {
        $LeaveId = $request->input('id');

        return view('admin.leave.leaveList', compact('LeaveId'));
    }


    public function adminLeaveTypeCreate(Request $request)
    {
        $leaveTypeId = $request->input('id');
        return view('admin.leave.leave_type_create', compact('leaveTypeId'));
    }
}

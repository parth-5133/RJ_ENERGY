<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;

class EmployeeResignationController extends Controller
{
    public function resignation(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.EMPLOYEERESIGNATION')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('employeeResignation.resignation_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function createResignation(Request $request)
    {
        $resignationId = $request->input('id');

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;


        if ($role_code == $this->employeeRoleCode) {
            return view('employeeResignation.resignation_create', compact('resignationId'));
        }

        if ($role_code == $this->AdminRoleCode || $role_code == $this->superAdminRoleCode && $resignationId == 0) {
            return view('employeeResignation.adminRresignation_create', compact('resignationId'));
        }

        if($resignationId > 0 && ($role_code == $this->AdminRoleCode || $role_code == $this->superAdminRoleCode)){
            return view('employeeResignation.adminRresignation_update', compact('resignationId'));
        }
    }
}

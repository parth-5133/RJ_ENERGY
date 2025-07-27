<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\AllowanceListController as ApiAllowanceListController;
use App\Http\Controllers\API\V1\DeductionController as ApiDeductionController;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    public function index(Request $request)
    {

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        $request->merge(['AccessCode' => config('menuAccessCode.EMPLOYEESALARY')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('employeeSalary.employeeSalary_index', ['permissions' => $permissions, 'menuName' => $menuName ,'role_code' => $role_code]);
    }
    public function create(Request $request)
    {
        // Get Allowances
        $apiController = new ApiAllowanceListController();
        $response = $apiController->index();
        $responseData = $response->getData(true);
        $allowanceList = $responseData['data'] ?? [];

        // Filter only active allowances
        $allowanceList = array_filter($allowanceList, function ($item) {
            return isset($item['is_active']) && $item['is_active'] == 1;
        });

        // Get Deductions
        $apiController = new ApiDeductionController();
        $response = $apiController->index();
        $responseData = $response->getData(true);
        $deductionList = $responseData['data'] ?? [];

        // Filter only active deductions
        $deductionList = array_filter($deductionList, function ($item) {
            return isset($item['is_active']) && $item['is_active'] == 1;
        });

        $EMployeeRoleId = $request->input('id');
        $isCopy = $request->input('params_id');

        return view('employeeSalary.employeeSalary_create', compact('EMployeeRoleId', 'allowanceList', 'deductionList', 'isCopy'));
    }
}

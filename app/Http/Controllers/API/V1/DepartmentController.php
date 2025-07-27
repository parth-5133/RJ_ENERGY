<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use App\Helpers\GetCompanyId;
use App\Helpers\JWTUtils;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateDepartmentRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $departments = DB::table('departments')
            ->where('departments.company_id', $CompanyId)
            ->leftJoin('users', 'departments.updated_by', '=', 'users.id')
            ->select(
                'departments.id',
                'departments.name',
                'departments.is_active',
                'departments.updated_by',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(departments.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('departments.deleted_at')
            ->orderBy('departments.id', 'desc')
            ->get();

        return ApiResponse::success($departments, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateDepartmentRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $Data = $request->all();
        $Data['created_by'] = $currentUser->id;
        $Data['created_at'] = now();
        $Data['updated_at'] = null;
        $Data['company_id'] = $CompanyId;

        $department = Department::create($Data);

        return ApiResponse::success($department, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $departmentId = $request->departmentId;
        $department = Department::find($departmentId);
        if ($department) {
            return ApiResponse::success($department, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($department, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateDepartmentRequest $request)
    {
        $departmentId = $request->departmentId;

        $department = Department::find($departmentId);

        if (!$department) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $department->fill($request->validated());
        $department->updated_by = $currentUser->id;
        $department->updated_at = now();

        $department->save();

        return ApiResponse::success($department, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $department = Department::find($id);

        if ($department) {
            $department->delete();
            return ApiResponse::success($department, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($department, ResMessages::NOT_FOUND);
        }
    }
}

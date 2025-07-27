<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAllowance;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateEmployeeAllowanceRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\GetYear;
use App\Helpers\FinancialYearService;

class AllowanceListController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $employeeAllowance = DB::table('employee_allowances')
            ->where('employee_allowances.company_id', $CompanyId)
            ->leftJoin('users', 'employee_allowances.updated_by', '=', 'users.id')
            ->select(
                'employee_allowances.id',
                'employee_allowances.allowances_name',
                'employee_allowances.is_active',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(employee_allowances.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('employee_allowances.deleted_at')
            ->orderBy('employee_allowances.id', 'desc')
            ->get();

        return ApiResponse::success($employeeAllowance, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(StoreUpdateEmployeeAllowanceRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $data = $request->validated();
        $data['created_by'] = $currentUser->id;
        $data['created_at'] = now();
        $data['updated_at'] = null;
        $data['company_id'] = $CompanyId;

        $employeeAllowance = EmployeeAllowance::create($data);

        return ApiResponse::success($employeeAllowance, ResMessages::CREATED_SUCCESS);
    }

    public function view(Request $request)
    {
        $Id = $request->allowanceId;

        $data = EmployeeAllowance::find($Id);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }

    public function update(StoreUpdateEmployeeAllowanceRequest $request)
    {
        $Id = $request->allowanceId;

        $data = EmployeeAllowance::find($Id);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data->fill($request->validated());
        $data->updated_by = $currentUser->id;
        $data->updated_at = now();

        $data->save();

        return ApiResponse::success($data,  ResMessages::UPDATED_SUCCESS);
    }

    public function delete($id)
    {
        $data = EmployeeAllowance::find($id);

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
}

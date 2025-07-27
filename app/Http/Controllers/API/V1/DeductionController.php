<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDeduction;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateEmployeeDeductionRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $EmployeeDeduction = DB::table('employee_deductions')
            ->where('employee_deductions.company_id', $CompanyId)
            ->leftJoin('users', 'employee_deductions.updated_by', '=', 'users.id')
            ->select(
                'employee_deductions.id',
                'employee_deductions.deduction_name',
                'employee_deductions.is_active',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(employee_deductions.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('employee_deductions.deleted_at')
            ->orderBy('employee_deductions.id', 'desc')
            ->get();

        return ApiResponse::success($EmployeeDeduction, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(StoreUpdateEmployeeDeductionRequest $request)
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

        $EmployeeDeduction = EmployeeDeduction::create($data);

        return ApiResponse::success($EmployeeDeduction, ResMessages::CREATED_SUCCESS);
    }

    public function view(Request $request)
    {
        $Id = $request->deductionId;

        $data = EmployeeDeduction::find($Id);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }

    public function update(StoreUpdateEmployeeDeductionRequest $request)
    {
        $Id = $request->deductionId;

        $data = EmployeeDeduction::find($Id);

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
        $data = EmployeeDeduction::find($id);

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
}

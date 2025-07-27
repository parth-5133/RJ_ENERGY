<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateFinancialYearRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use App\Models\Holiday;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;

class FinancialYearController extends Controller
{
    public function index()
    {
        $companiesId = GetCompanyId::GetCompanyId();

        $financialYears = DB::table('financial_years')
            ->leftJoin('users', 'financial_years.updated_by', '=', 'users.id')
            ->select(
                'financial_years.id',
                DB::raw("DATE_FORMAT(financial_years.from_date, '%d/%m/%Y') as from_date"),
                DB::raw("DATE_FORMAT(financial_years.to_date, '%d/%m/%Y') as to_date"),
                'financial_years.display_year',
                'financial_years.is_currentYear',
                'financial_years.is_active',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(financial_years.updated_at, '%d/%m/%Y') as updated_at")
            );

        if ($companiesId) {
            $financialYears->where('financial_years.company_id', $companiesId);
        }

        $financialYears = $financialYears->get();

        return ApiResponse::success($financialYears, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createFinancialYear(CreateFinancialYearRequest $request)
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



        if ($Data['is_currentYear'] == true) {
            $Data['is_currentYear'] = true;
            FinancialYear::where('company_id', $Data['company_id'])->update(['is_currentYear' => 0]);
        } else {
            $Data['is_currentYear'] = false;
        }

        $Data = FinancialYear::create($Data);

        return ApiResponse::success($Data, ResMessages::CREATED_SUCCESS);
    }
    public function viewFinancialYear(Request $request)
    {
        $FinancialYearId = $request->FinancialYearId;

        $data = FinancialYear::find($FinancialYearId);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function updateFinancialYear(CreateFinancialYearRequest $request)
    {
        $FinancialYearId = $request->FinancialYearId;

        $data = FinancialYear::find($FinancialYearId);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data->fill($request->validated());
        $data->updated_by = $currentUser->id;
        $data->updated_at = now();

        if ($data->is_currentYear == true) {
            $data->is_currentYear = true;
            FinancialYear::where('company_id', $data->company_id)->update(['is_currentYear' => 0]);
        } else {
            $data->is_currentYear = false;
        }

        $data->save();

        return ApiResponse::success($data,  ResMessages::UPDATED_SUCCESS);
    }
    public function deleteFinancialYear($id)
    {
        $data = FinancialYear::find($id);

        if ($data->is_currentYear == 1) {
            return ApiResponse::error(null, 'Cannot delete the current financial year.');
        }

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
}

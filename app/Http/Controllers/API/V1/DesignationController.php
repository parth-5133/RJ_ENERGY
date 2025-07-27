<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateDesignationRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $designations = DB::table('designations')
            ->where('designations.company_id', $CompanyId)
            ->leftJoin('users', 'designations.updated_by', '=', 'users.id')
            ->select(
                'designations.id',
                'designations.name',
                'designations.is_active',
                'designations.updated_by',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(designations.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('designations.deleted_at')
            ->orderBy('designations.id', 'desc')
            ->get();

        return ApiResponse::success($designations, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateDesignationRequest $request)
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

        $designation = Designation::create($Data);

        return ApiResponse::success($designation, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $Id = $request->designationId;

        $data = Designation::find($Id);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateDesignationRequest $request)
    {
        $Id = $request->designationId;

        $data = Designation::find($Id);

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
        $data = Designation::find($id);

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
}

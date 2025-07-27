<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use App\Helpers\GetCompanyId;
use App\Helpers\JWTUtils;
use App\Http\Requests\StoreUpdateShiftRequest;
use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $shifts = DB::table('employees_shift')
            ->where('employees_shift.company_id', $CompanyId)
            ->leftJoin('users', 'employees_shift.updated_by', '=', 'users.id')
            ->select(
                'employees_shift.id',
                'employees_shift.shift_name',
                'employees_shift.from_time',
                'employees_shift.to_time',
                'employees_shift.is_active',
                'employees_shift.updated_by',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(employees_shift.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('employees_shift.deleted_at')
            ->orderBy('employees_shift.id', 'desc')
            ->get();

        return ApiResponse::success($shifts, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateShiftRequest $request)
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

        $shift = Shift::create($Data);

        return ApiResponse::success($shift, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $Id = $request->shiftId;

        $data = Shift::find($Id);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateShiftRequest $request)
    {
        $Id = $request->shiftId;

        $data = Shift::find($Id);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data->fill($request->validated());
        $data->updated_by = $currentUser->id;
        $data->updated_at = now();

        $data->save();

        return ApiResponse::success($data, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $shift = Shift::find($id);

        if ($shift) {
            $shift->delete();
            return ApiResponse::success($shift, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($shift, ResMessages::NOT_FOUND);
        }
    }
}

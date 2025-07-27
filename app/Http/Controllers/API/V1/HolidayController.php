<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateHolidayRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetYear;
use App\Helpers\GetCompanyId;
use App\Helpers\FinancialYearService;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year');
        $financialYears = GetYear::getYear();
        $companiesId = GetCompanyId::GetCompanyId();

        $holidayQuery = DB::table('holidays')
            ->leftJoin('users', 'holidays.updated_by', '=', 'users.id')
            ->select(
                'holidays.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(holidays.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                DB::raw("DATE_FORMAT(holidays.holiday_date, '%d/%m/%Y') as holiday_date_formatted")
            )
            ->whereNull('holidays.deleted_at');
        if ($companiesId) {
            $holidayQuery->where('holidays.company_id', $companiesId);
        }
        if ($year) {
            $holidayQuery->whereYear('holidays.holiday_date', $year);
        }
        if ($financialYears && isset($financialYears->id)) {
            $holidayQuery->where('holidays.financialYear_id', $financialYears->id);
        }
        $holidays = $holidayQuery->orderBy('holidays.holiday_date', 'asc')->get();

        return ApiResponse::success($holidays, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createHoliday(CreateHolidayRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $financialYears = FinancialYearService::getCurrentFinancialYear();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $HoliDayData = $request->all();
        $HoliDayData['day'] = \Carbon\Carbon::parse($HoliDayData['holiday_date'])->format('l');
        $HoliDayData['created_by'] = $currentUser->id;
        $HoliDayData['created_at'] = now();
        $HoliDayData['updated_at'] = null;
        $HoliDayData['financialYear_id'] = $financialYears->id;
        $HoliDayData['company_id'] = $CompanyId;

        $HoliDay = Holiday::create($HoliDayData);

        return ApiResponse::success($HoliDay, ResMessages::CREATED_SUCCESS);
    }
    public function viewHoliday(Request $request)
    {
        $holidays = $request->holidayId;

        $holidays = Holiday::find($holidays);
        if ($holidays) {
            return ApiResponse::success($holidays, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($holidays, ResMessages::NOT_FOUND);
        }
    }
    public function updateHoliday(CreateHolidayRequest $request)
    {
        $holidayId = $request->holiday_id;

        $holiday = Holiday::find($holidayId);

        if (!$holiday) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $holiday->fill($request->validated());
        $holiday->updated_by = $currentUser->id;
        $holiday->updated_at = now();

        $holiday->save();

        return ApiResponse::success($holiday,  ResMessages::UPDATED_SUCCESS);
    }
    public function deleteHoliday($id)
    {
        $holidays = Holiday::find($id);

        if ($holidays) {
            $holidays->delete();
            return ApiResponse::success($holidays, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($holidays, ResMessages::NOT_FOUND);
        }
    }
}

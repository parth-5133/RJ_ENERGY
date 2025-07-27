<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use Carbon\Carbon;


class superAdminDashboardController extends Controller
{
    public function getCompanyStatusOverview()
    {
        $data = DB::table('companies')
            ->select('id', 'is_active', 'deleted_at')
            ->get();


        $activeCompanies = $data->where('is_active', '=', 1)->whereNull('deleted_at')->count();
        $inactiveCompanies = $data->where('is_active', '=', 0)->whereNull('deleted_at')->count();
        $lostCompanies = $data->where('deleted_at', '!=', null)->count();

        $data = [
            'active' => $activeCompanies,
            'inactive' => $inactiveCompanies,
            'lost' => $lostCompanies,
        ];

        return ApiResponse::success($data, ResMessages::DELETED_SUCCESS, 10);
    }
    public function getCompanyNumber()
    {
        $data = DB::table('companies')
            ->select('id', 'is_active', 'deleted_at', 'created_at')
            ->get();

        $totalCompanies = $data->count();
        $activeCompanies = $data->where('is_active', '=', 1)->whereNotNull('deleted_at')->count();
        $inactiveCompanies = $data->where('is_active', '=', 0)->count();
        $lostCompanies = $data->where('deleted_at', '!=', null)->count();
        $thisWeekCompanies = $data->where('created_at', '>=', Carbon::now()->startOfWeek())->count();

        $data = [
            'active' => $activeCompanies,
            'inactive' => $inactiveCompanies,
            'lost' => $lostCompanies,
            'totalCompanies' => $totalCompanies,
            'thisWeekCompanies' => $thisWeekCompanies,
        ];

        return ApiResponse::success($data, ResMessages::DELETED_SUCCESS, 10);
    }
}

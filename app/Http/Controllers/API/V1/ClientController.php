<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateRoleRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::getCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $RoleId = Role::where('code', $this->clientRoleCode)
            ->where('company_id', $CompanyId)
            ->whereNull('deleted_at')
            ->first()->id;

        $usersQuery = DB::table('users')
            ->leftJoin('users as updater', 'users.updated_by', '=', 'updater.id')
            ->select(
                'users.id',
                'users.email',
                'users.is_active',
                'users.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.middle_name, ''), ' ', users.last_name) as name"),
                DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(users.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->where('users.company_id', $CompanyId)
            ->where('users.role_id', $RoleId)
            ->whereNull('users.deleted_at');

        $users = $usersQuery->orderByDesc('users.id')->get();

        return ApiResponse::success($users, ResMessages::RETRIEVED_SUCCESS);
    }
}

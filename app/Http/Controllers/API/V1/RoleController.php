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


class RoleController extends Controller
{
    public function index()
    {
        $currentAccessLevel = AccessLevel::getAccessLevel();
        $companiesId = GetCompanyId::GetCompanyId();

        $roles = DB::table('roles')
            ->leftJoin('users', 'roles.updated_by', '=', 'users.id')
            ->whereNull('roles.deleted_at')
            ->where('roles.access_level', '<', $currentAccessLevel->access_level)
            ->select(
                'roles.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(roles.updated_at, '%d/%m/%Y') as updated_at_formatted")
            );

        if ($companiesId) {
            $roles->where('roles.company_id', $companiesId);
        }

        $roles = $roles->orderByDesc('id')->get();

        return ApiResponse::success($roles, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateRoleRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $roleData = $request->all();
        $roleData['created_by'] = $currentUser->id;
        $roleData['created_at'] = now();
        $roleData['updated_at'] = null;
        $roleData['company_id'] = $CompanyId;

        $role = Role::create($roleData);

        return ApiResponse::success($role, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $roleId = $request->roleId;

        $role = Role::find($roleId);
        if ($role) {
            return ApiResponse::success($role, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($role, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateRoleRequest $request)
    {
        $roleId = $request->roleId;

        $role = Role::find($roleId);

        if (!$role) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $role->fill($request->validated());
        $role->updated_by = $currentUser->id;
        $role->updated_at = now();

        // Save the updated Role
        $role->save();

        return ApiResponse::success($role,  ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $role = Role::find($id);

        if ($role) {

            $userCount = DB::table('users')->where('role_id', $id)->count();

            if ($userCount > 0) {
                return ApiResponse::error(null, 'This role cannot be deleted as it is still assigned to one or more users.');
            }
            $role->delete();
            return ApiResponse::success($role, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
}

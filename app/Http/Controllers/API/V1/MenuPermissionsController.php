<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use App\Models\RolePermissionMapping;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\Cache;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;


class MenuPermissionsController extends Controller
{
    public function index(Request $request)
    {

        $code = $request->AccessCode;
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $cookieData = request()->cookie('access_token');
        $token = str_replace('Bearer ', '', $cookieData);

        $currentUser = JWTUtils::getCurrentUserByUuid($token);

        $cacheKey = "permissions_{$currentUser->role_id}_{$code}";

        // Cache permissions for 10 minutes (600 seconds)
        $permissions = Cache::remember($cacheKey, 600, function () use ($code, $currentUser) {
            return RolePermissionMapping::join('menus', 'role_permission_mapping.menu_id', '=', 'menus.id')
                ->where('menus.access_code', $code)
                ->where('role_permission_mapping.role_id', $currentUser->role_id)
                ->select('role_permission_mapping.*', 'menus.name as menu_name')
                ->first();
        });

        $data = $permissions;

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function getMenuByUserRights()
    {
        $cookieData = request()->cookie('access_token');
        $token = str_replace('Bearer ', '', $cookieData);

        $currentUser = JWTUtils::getCurrentUserByUuid($token);

        $user = $currentUser->id;

        $roleId = User::where('id', $user)->value('role_id');

        // Removed Cache::remember, fetch menus directly
        // Fetch role permission mappings where the user can view menus
        $permissions = RolePermissionMapping::whereIn('role_id', [$roleId])
            ->where('canView', 1)
            ->select('menu_id')
            ->get();


        // Extract menu_ids from permissions
        $menuIds = $permissions->pluck('menu_id');

        $menus = Menu::whereIn('id', function ($query) use ($menuIds) {
            $query->select('parent_menu_id')
                ->from('menus')
                ->whereIn('id', $menuIds);
        })
            ->orWhereIn('id', $menuIds)
            ->where('display_in_menu', 1)
            ->orderBy('display_order', 'asc')
            ->get([
                'id',
                'name',
                'access_code',
                'navigation_url',
                'display_in_menu',
                'parent_menu_id',
                'menu_icon',
                'menu_class',
                'display_order',
                'company_id'
            ]);

        $response = [
            'menus' => $menus
        ];
        return ApiResponse::success($response, ResMessages::RETRIEVED_SUCCESS);
    }
}

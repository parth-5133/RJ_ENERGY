<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateMenuRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MenuController extends Controller
{
    public function index()
    {
        $company_id = GetCompanyId::GetCompanyId();

        $menus = DB::table('menus')
            ->leftJoin('menus as parent_menu', 'menus.parent_menu_id', '=', 'parent_menu.id')
            ->leftJoin('users', 'menus.updated_by', '=', 'users.id')
            ->select(
                'menus.id',
                'menus.name',
                'menus.access_code',
                'menus.navigation_url',
                'menus.is_active',
                'menus.parent_menu_id',
                'menus.display_order',
                DB::raw("DATE_FORMAT(menus.updated_at, '%d/%m/%Y') as updated_at_formatted"),
                'parent_menu.name as parent_menu_name',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name")
            )
            ->whereNull('menus.deleted_at');

        if ($company_id) {
            $menus->where('menus.company_id', $company_id);
        }

        $menus = $menus->orderBy('display_order')->get();

        return ApiResponse::success($menus, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(StoreUpdateMenuRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $menuData = $request->all();
        $menuData['created_by'] = $currentUser->id;
        $menuData['created_at'] = now();
        $menuData['updated_at'] = null;
        $menuData['company_id'] = $CompanyId;

        if ($menuData['parent_menu_id'] == 0) {
            $lastMenu = Menu::where('parent_menu_id', 0)
                ->orderBy('display_order', 'desc')
                ->first();

            $menuData['display_order'] = $lastMenu ? $lastMenu->display_order + 1 : 1;
        } else {
            $lastChildMenu = Menu::where('parent_menu_id', $menuData['parent_menu_id'])
                ->orderBy('display_order', 'desc')
                ->first();
            $menuData['display_order'] = $lastChildMenu ? $lastChildMenu->display_order + 1 : 1;
        }

        $menu = Menu::create($menuData);

        $cacheKey = "header_data_{$currentUser->id}";
        Cache::forget($cacheKey);

        return ApiResponse::success($menu, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $menuId = $request->menuId;

        $Menu = Menu::find($menuId);
        if ($Menu) {
            return ApiResponse::success($Menu, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($Menu, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateMenuRequest $request)
    {
        $menuId = $request->menuId;

        $menu = Menu::find($menuId);

        if (!$menu) {
            return ApiResponse::error(ResMessages::NOT_FOUND, []);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $menuData = $request->validated();
        $menu->fill($menuData);
        $menu->updated_by = $currentUser->id;
        $menu->updated_at = now();

        if ($menuData['parent_menu_id'] == 0) {
            $lastMenu = Menu::where('parent_menu_id', 0)
                ->orderBy('display_order', 'desc')
                ->first();

            $menu->display_order = $lastMenu ? $lastMenu->display_order + 1 : 1;
        } else {
            $lastChildMenu = Menu::where('parent_menu_id', $menuData['parent_menu_id'])
                ->orderBy('display_order', 'desc')
                ->first();

            $menu->display_order = $lastChildMenu ? $lastChildMenu->display_order + 1 : 1;
        }

        $menu->save();

        $cacheKey = "header_data_{$currentUser->id}";
        Cache::forget($cacheKey);

        return ApiResponse::success($menu, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $Menu = Menu::find($id);

        if ($Menu) {
            $Menu->delete();
            return ApiResponse::success($Menu, ResMessages::DELETED_SUCCESS, 1);
        } else {
            return ApiResponse::error($Menu, ResMessages::NOT_FOUND,);
        }
    }
    public function menuReorder(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $id = $request->input('id');
        $direction = $request->input('direction');

        $currentMenu = Menu::find($id);
        if (!$currentMenu) {
            return response()->json(['success' => false, 'message' => 'Menu item not found.'], 404);
        }

        // Determine if this is a parent menu or child menu
        $isParentMenu = $currentMenu->parent_menu_id == 0;

        if ($direction == 'up') {
            $adjacentMenu = Menu::where('display_order', '<', $currentMenu->display_order)
                ->when(!$isParentMenu, function ($query) use ($currentMenu) {
                    $query->where('parent_menu_id', $currentMenu->parent_menu_id);
                })
                ->when($isParentMenu, function ($query) {
                    $query->where('parent_menu_id', 0);
                })
                ->orderBy('display_order', 'desc')
                ->first();
        } elseif ($direction == 'down') {
            $adjacentMenu = Menu::where('display_order', '>', $currentMenu->display_order)
                ->when(!$isParentMenu, function ($query) use ($currentMenu) {
                    $query->where('parent_menu_id', $currentMenu->parent_menu_id);
                })
                ->when($isParentMenu, function ($query) {
                    $query->where('parent_menu_id', 0);
                })
                ->orderBy('display_order', 'asc')
                ->first();
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid direction.'], 400);
        }

        if (!$adjacentMenu) {
            return response()->json(['success' => false, 'message' => 'No adjacent menu item to swap with.'], 404);
        }

        // Swap display_order values
        $tempOrder = $currentMenu->display_order;
        $currentMenu->display_order = $adjacentMenu->display_order;
        $currentMenu->updated_by = $currentUser->id;

        $adjacentMenu->display_order = $tempOrder;
        $adjacentMenu->updated_by = $currentUser->id;

        $currentMenu->save();
        $adjacentMenu->save();

        return ApiResponse::success(null, ResMessages::UPDATED_SUCCESS);
    }
}

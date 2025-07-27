<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\MenuController as ApiMenuController;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.MENU')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        $apiController = new ApiMenuController();
        $response = $apiController->index();
        $responseData = $response->getData(true);
        $menus = $responseData['data'] ?? [];
        
        return view('menu.menu_index', ['permissions' => $permissions, 'menuName' => $menuName, 'menus' => $menus]);
    }

    public function create(Request $request)
    {
        $menuId = $request->input('id');

        return view('menu.menu_create', compact('menuId'));
    }
}

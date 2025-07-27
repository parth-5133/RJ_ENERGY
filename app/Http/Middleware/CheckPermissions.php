<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use App\Constants\ResStatusCode;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class CheckPermissions
{

    public function handle($request, Closure $next, $permission, $menu_access_code)
    {
        // Define the cache key
        $cacheKey = "menus";

        // Retrieve menus from cache or database if not cached
        $menus = Cache::remember($cacheKey, 600, function () {
            return Menu::all()->keyBy('access_code'); // Ensure menus are keyed by access_code
        });

        // Check if a menu_access_code is provided
        if (!is_null($menu_access_code)) {
            // Retrieve the menu using the access code
            $menu = $menus->get($menu_access_code); // Directly get the menu

            // Validate the retrieved menu
            if (!$menu) { // Check if the menu is null
                return ApiResponse::verifyError('Menu not found', ResStatusCode::BAD_REQUEST);
            }

            // Extract the menu ID for permission checking
            $menuId = $menu->id; // Access the ID from the individual menu item

            // Check if the user is authenticated and has the required permission for the menu
            if (Auth::check() && Auth::user()->hasPermission($permission, $menuId)) {
                return $next($request); // Proceed with the next middleware/request
            }
        }

        // Return an error response if the user lacks permission
        return ApiResponse::verifyError('Do not have Permission', ResStatusCode::UNAUTHORIZED);
    }
}

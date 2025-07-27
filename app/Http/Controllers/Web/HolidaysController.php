<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;


class HolidaysController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.HOLIDAY')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('holiday.holiday_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function create(Request $request)
    {
        $holidayId = $request->input('id');

        return view('holiday.holiday_create', compact('holidayId'));
    }
}

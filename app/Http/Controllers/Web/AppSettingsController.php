<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.EMAILSETTINGS')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401');
        }

        return view('emailSettings.emailSettings_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }
    public function create(Request $request)
    {
        $emailSettingsId = $request->input('id');

        return view('emailSettings.emailSettings_create', compact('emailSettingsId'));
    }

    public function notificationsIndex(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.NOTIFICATIONSSETTING')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';
        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401');
        }

        return view('notifications-Settings.notifications_setting', ['permissions' => $permissions, 'menuName' => $menuName]);
    }
}

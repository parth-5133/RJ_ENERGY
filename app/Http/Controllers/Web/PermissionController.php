<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index(Request $request)
    {

        $request->merge(['AccessCode' => config('menuAccessCode.MENUROLEMAPPING')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        if (empty($permissions) || empty($permissions['canView'])) {

            return response()->view('errors.401',);
        }

        return view('permission.permission_index', ['permissions' => $permissions,]);
    }
}

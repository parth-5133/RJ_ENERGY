<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Helpers\JWTUtils;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.CLIENTLIST')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401');
        }

        return view('client.client_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function create(Request $request)
    {
        $clientId = $request->input('id');

        return view('client.client_create', compact('clientId'));
    }
}

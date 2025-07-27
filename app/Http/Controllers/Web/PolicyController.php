<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\PolicyController as ApiPolicyController;
use Illuminate\Http\Request;


class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['AccessCode' => config('menuAccessCode.POLICIESLIST')]);

        $apiController = new MenuPermissionsController();
        $response = $apiController->index($request);

        $responseData = $response->getData(true);

        $permissions = $responseData['data'] ?? [];

        $menuName = $permissions['menu_name'] ?? '';

        if (empty($permissions) || empty($permissions['canView'])) {
            return response()->view('errors.401',);
        }

        return view('policy.policy_index', ['permissions' => $permissions, 'menuName' => $menuName]);
    }

    public function create(Request $request)
    {
        $policyId = $request->input('id');

        return view('policy.policy_create', compact('policyId'));
    }

    public function view(Request $request)
    {
        $policyId = $request->input('id');

        $request->merge(['policyId' => $policyId]);

        $apiController = new ApiPolicyController();
        $response = $apiController->view($request);
        $responseData = $response->getData(true);
        $policyData = $responseData['data'] ?? [];
        $policyDescription = $policyData['policy_description'] ?? '';

        return view('policy.policy_view', compact('policyId', 'policyDescription'));
    }
}

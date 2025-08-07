<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\MenuPermissionsController;
use App\Http\Controllers\API\V1\usersController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\CompanyController;
use App\Helpers\GetYear;
use App\Helpers\GetCompanyId;

class LayoutController extends Controller
{
    public function showMenu()
    {
        $apiController = new MenuPermissionsController();
        $response = $apiController->getMenuByUserRights();

        $responseData = $response->getData(true);
        $menus = $responseData['data']['menus'] ?? [];

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        return view('includes.aside', compact('menus'));
    }
    public function showProfile(Request $request = null)
    {
        $financialYears = GetYear::getYear();
        $financialYearsId = $financialYears->id ?? null;
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $name = $cookieData['name'] ?? null;
        $role_name = $cookieData['role_name'] ?? null;
        $profile_img = $cookieData['profile_img'] ?? null;
        $role_code = $cookieData['role_code'] ?? null;
        $company_name = $cookieData['company_name'] ?? null;

        $apiController = new usersController();
        $response = $apiController->showNotifications();

        if ($response == null) {
            $notifications = [];
        } else {
            $responseData = $response->getData(true);
            $notifications = $responseData['data'] ?? [];
        }

        $notifications = collect($notifications);   

        $apiController = new DashboardController();
        $response = $apiController->getCompanies();

        $responseData = $response->getData(true);
        $companies = $responseData['data'] ?? [];

        $apiController = new DashboardController();
        $response = $apiController->getFinancialYears();

        $responseData = $response->getData(true);
        $financial_years = $responseData['data'] ?? [];

        $companyId = GetCompanyId::GetCompanyId();
        if ($request == null) {
            $request = new Request();
        }
        $request->merge(['companyId' => $companyId]);

        $apiController = new CompanyController();
        $response = $apiController->viewCompany($request);

        $responseData = $response->getData(true);
        $company_logo = $responseData['data']['logo_url'] ?? null;

        return view('includes.nav', compact('name', 'role_name', 'role_code', 'profile_img', 'notifications', 'financial_years', 'financialYearsId', 'companies', 'companyId', 'company_name', 'company_logo'));
    }
}

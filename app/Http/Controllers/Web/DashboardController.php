<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\ClientController as ApiClientController;
use App\Http\Controllers\API\V1\superAdminDashboardController as ApiSuperAdminDashboardController;
use App\Http\Controllers\API\V1\usersController as ApiUsersController;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cookieData = json_decode($request->cookie('user_data'), true);
        $roleCode = $cookieData['role_code'] ?? null;
        $name = $cookieData['name'] ?? null;
        $profileImg = $cookieData['profile_img'] ?? null;

        if ($roleCode === $this->employeeRoleCode || $roleCode === $this->AdminRoleCode) {
            $birthdayData = $this->getTodaysBirthday();
            $employeesList = $this->getEmployeesList($roleCode);
        }

        if ($roleCode === $this->AdminRoleCode) {

            return view('dashboard.Admin_dashboard', compact('name', 'profileImg', 'birthdayData', 'employeesList'));
        }

        if ($roleCode === $this->superAdminRoleCode) {

            return view('dashboard.superAdmin_dashboard', compact('name', 'profileImg'));
        }
        return match ($roleCode) {
            $this->clientRoleCode => view('dashboard.client_dashboard', compact('name', 'profileImg')),
            default => view('dashboard.employee_dashboard', compact('name', 'profileImg', 'birthdayData', 'employeesList')),
        };
    }


    public function getCompanyOverview()
    {
        $apiController = new ApiSuperAdminDashboardController();

        $response = $apiController->getCompanyNumber();

        return $response->getData(true)['data'] ?? [];
    }
    public function getTodaysBirthday()
    {
        $apiController = new ApiUsersController();
        $response = $apiController->getTodaysBirthday();

        return $response->getData(true)['data'] ?? [];
    }
    public function getEmployeesList($roleCode)
    {
        if ($roleCode === $this->AdminRoleCode) {
            $isEmployee = false;
        } else {
            $isEmployee = true;
        }

        $apiController = new ApiUsersController();
        $response = $apiController->getEmployeesList($isEmployee);

        return $response->getData(true)['data'] ?? [];
    }

    public function benefits()
    {
        return view('consumer.client_benefits');
    }
}

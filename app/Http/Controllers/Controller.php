<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $employeeRoleCode;
    protected $superAdminRoleCode;
    protected $clientRoleCode;
    protected $AdminRoleCode;

    public function __construct()
    {
        $this->employeeRoleCode = config('roles.EMPLOYEE');

        $this->superAdminRoleCode = config('roles.SUPERADMIN');

        $this->clientRoleCode = config('roles.CLIENT');

        $this->AdminRoleCode = config('roles.ADMIN');
    }
}

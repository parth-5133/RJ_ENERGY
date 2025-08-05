<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\MenuController;
use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ErrorController;
use App\Http\Controllers\Web\HolidaysController;
use App\Http\Controllers\Web\LeaveController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\FinancialYearController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\AllowanceListController;
use App\Http\Controllers\Web\DeductionController;
use App\Http\Controllers\Web\EmployeeSalaryController;
use App\Http\Controllers\Web\PolicyController;
use App\Http\Controllers\Web\AppSettingsController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\LayoutController;
use App\Http\Controllers\Web\EmployeeResignationController;
use App\Http\Controllers\Web\ConsumerApplicationController;
use App\Http\Controllers\Web\ChannelPartnersController;
use App\Http\Controllers\Web\ManageBankController;
use App\Http\Controllers\Web\InstallersController;
use App\Http\Controllers\Web\QuotesController;


Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [WebAuthController::class, 'index'])->name('login');
    Route::post('loginPost', [WebAuthController::class, 'loginPost'])->name('loginPost');
    Route::get('/register', [WebAuthController::class, 'register'])->name('register');
    Route::post('/registerPost', [WebAuthController::class, 'registerPost'])->name('registerPost');
    Route::get('/forgotPassword', [WebAuthController::class, 'forgotPassword'])->name('forgotPassword');
    Route::get('password/reset/{token}/{email}', [WebAuthController::class, 'showResetForm'])->name('password.reset');
});

Route::middleware(['CheckAuth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change/password', [WebAuthController::class, 'changePassword'])->name('change.Password');

    Route::get('/applications/create', [ConsumerApplicationController::class, 'create'])->name('roles.create');
    Route::get('/My-documents', [ConsumerApplicationController::class, 'MydocumentsList'])->name('My-documents');
    Route::get('/customer/benefits', [DashboardController::class, 'benefits'])->name('customer.benefits');
    Route::get('/My-applications/list', [ConsumerApplicationController::class, 'index'])->name('My-applications.list');

    // Role
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('roles.create');

    // User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');

    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create');

    // navbar
    Route::get('/navbar', [LayoutController::class, 'showProfile'])->name('showProfile');

    // Menu Permission
    Route::get('/permission/{id}', [PermissionController::class, 'index'])->name('permission');

    // Profile Section routes
    Route::get('/profile', [ProfileController::class, 'profile'])->name('web.profile');
    Route::get('/address', [ProfileController::class, 'address'])->name('address');
    Route::get('/education', [ProfileController::class, 'education'])->name('education');
    Route::get('/documents', [ProfileController::class, 'documents'])->name('documents');
    Route::get('/financial', [ProfileController::class, 'financial'])->name('financial');
    Route::get('/experience', [ProfileController::class, 'experience'])->name('experience');
    Route::get('/vehicle', [ProfileController::class, 'vehicle'])->name('vehicle');
    Route::get('/job', [ProfileController::class, 'job'])->name('job');

    //leave
    Route::get('/leaves', [LeaveController::class, 'index'])->name('Leave');
    Route::get('/leave/report', [LeaveController::class, 'leaveReportData']);
    Route::get('/leave/setting', [LeaveController::class, 'leaveSetting']);
    Route::get('/employee/leaves/request', [LeaveController::class, 'employeeLeavesRequest']);
    Route::get('/leaves/report', [LeaveController::class, 'adminLeaveReport']);
    Route::get('/admin/leaves/request', [LeaveController::class, 'adminLeaveRequest']);
    Route::get('/leaves/type/create', [LeaveController::class, 'adminLeaveTypeCreate']);

    // Attendance
    Route::get('/attendance/request', [AttendanceController::class, 'index']);
    Route::get('/attendance/report', [AttendanceController::class, 'attendanceReport'])->name('Attendance.report');
    Route::get('admin/attendance/request', [AttendanceController::class, 'adminAttendanceRequest']);
    Route::get('admin/attendance/request/edit', [AttendanceController::class, 'adminAttendanceRequestEdit']);
    Route::get('/employee/attendance/request', [AttendanceController::class, 'requestAttendance']);

    // Holidays
    Route::get('/holidays', [HolidaysController::class, 'index'])->name('holidays');
    Route::get('/holidays/create', [HolidaysController::class, 'create'])->name('holidays.create');

    // Company
    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');

    // Financial Year
    Route::get('/financial-year', [FinancialYearController::class, 'index'])->name('financial-year');
    Route::get('/financial-year/create', [FinancialYearController::class, 'create'])->name('financial-year.create');

    // Employee
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee');

    // Allowance List
    Route::get('/allowance', [AllowanceListController::class, 'index'])->name('allowance-list');
    Route::get('/allowance/create', [AllowanceListController::class, 'create'])->name('allowance-create');

    // DeductionList List
    Route::get('/deduction', [DeductionController::class, 'index'])->name('deduction-list');
    Route::get('/deduction/create', [DeductionController::class, 'create'])->name('deduction-create');

    // Employee Salary
    Route::get('/employee-salary', [EmployeeSalaryController::class, 'index'])->name('employee-salary');
    Route::get('/employee-salary/create', [EmployeeSalaryController::class, 'create'])->name('employee-salary.create');

    // Employee Resignation
    Route::get('/resignation', [EmployeeResignationController::class, 'resignation'])->name('resignation');
    Route::get('/resignation/create', [EmployeeResignationController::class, 'createResignation'])->name('resignation.create');

    // Privacy Policy
    Route::get('/policy', [PolicyController::class, 'index'])->name('policy');
    Route::get('/policy/create', [PolicyController::class, 'create'])->name('policy.create');
    Route::get('/policy/view', [PolicyController::class, 'view'])->name('policy.view');

    // App Settings
    Route::get('/email-settings', [AppSettingsController::class, 'index'])->name('email-settings');
    Route::get('/email-settings/create', [AppSettingsController::class, 'create'])->name('email-settings.create');

    Route::get('/notifications-settings', [AppSettingsController::class, 'notificationsIndex'])->name('notifications-settings');


    // Channel Partners
    Route::get('/channel-partners', [ChannelPartnersController::class, 'index'])->name('channel-partners');
    Route::get('/channel-partners/create', [ChannelPartnersController::class, 'create'])->name('channel-partners.create');

    // Manage Bank
    Route::get('/manage-bank', [ManageBankController::class, 'index'])->name('manage-bank');
    Route::get('/manage-bank/create', [ManageBankController::class, 'create'])->name('manage-bank.create');

    // Installers
    Route::get('/installers', [InstallersController::class, 'index'])->name('installers');
    Route::get('/installers/create', [InstallersController::class, 'create'])->name('installers.create');

    // Quotations
    Route::get('/quotation-list', [QuotesController::class, 'index'])->name('quotes');
    Route::get('/quotation/create', [QuotesController::class, 'create'])->name('quotes.create');

    // client
    Route::get('/client', [ClientController::class, 'index'])->name('client');
    Route::get('/client/create', [ClientController::class, 'create'])->name('customer.create');
    Route::get('/client/details/{id}', [ClientController::class, 'showDetails'])->name('client.details');
    Route::get('/client/documents/upload', [ClientController::class, 'uploadDocuments'])->name('client.documents.upload');
});

Route::get('/401', [ErrorController::class, 'index'])->name('unauthorized.401');

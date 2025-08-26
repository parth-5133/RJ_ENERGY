<?php

return [

    'CHANNEL_PARTNERS_URLS' => [
        'CHANNEL_PARTNERS' => env('APP_URL') . env('API_BASE_PATH') . '/channel-partners',
        'CHANNEL_PARTNERS_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/channel-partners/create',
        'CHANNEL_PARTNERS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/channel-partners/view',
        'CHANNEL_PARTNERS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/channel-partners/update',
        'CHANNEL_PARTNERS_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/channel-partners/delete',
    ],

    'MANAGE_BANK_URLS' => [
        'MANAGE_BANK' => env('APP_URL') . env('API_BASE_PATH') . '/manage-bank',
        'MANAGE_BANK_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/manage-bank/create',
        'MANAGE_BANK_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/manage-bank/view',
        'MANAGE_BANK_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/manage-bank/update',
        'MANAGE_BANK_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/manage-bank/delete',
    ],

    'INSTALLERS_URLS' => [
        'INSTALLERS' => env('APP_URL') . env('API_BASE_PATH') . '/installers',
        'INSTALLERS_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/installers/create',
        'INSTALLERS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/installers/view',
        'INSTALLERS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/installers/update',
        'INSTALLERS_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/installers/delete',
    ],

    'QUOTATION_URLS' => [
        'QUOTATION' => env('APP_URL') . env('API_BASE_PATH') . '/quotation',
        'QUOTATION_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/quotation/create',
        'QUOTATION_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/quotation/view',
        'QUOTATION_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/quotation/update',
        'QUOTATION_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/quotation/delete',
        'QUOTATION_ALL_ACCOUNTANT' => env('APP_URL') . env('API_BASE_PATH') . '/getAccountant-list',
    ],

    'CLIENT_URLS' => [
        'CLIENT' => env('APP_URL') . env('API_BASE_PATH') . '/client-application',
        'CLIENT_ACCEPT' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/accept',
        'CLIENT_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/create',
        'CLIENT_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/view',
        'CLIENT_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/update',
        'CLIENT_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/delete',
        'CLIENT_UPLOAD_DOCUMENT' => env('APP_URL') . env('API_BASE_PATH') . '/client-application/documents/upload',
    ],

    'AUTH_URL' => [

        'LOGIN' => env('APP_URL') . env('API_BASE_PATH') . '/login',
        'CHANGE_PASSWORD' => env('APP_URL') . env('API_BASE_PATH') . '/change/password',
        'FORGOT_PASSWORD' => env('APP_URL') . env('API_BASE_PATH') . '/forgot-password',
        'RESET_PASSWORD' => env('APP_URL') . env('API_BASE_PATH') . '/password/reset',
        'LOGOUT' => env('APP_URL') . env('API_BASE_PATH') . '/logout',
    ],

    'PROFILE_URLS' => [

        'PROFILE' => env('APP_URL') . env('API_BASE_PATH') . '/profile',
        'JOB' => env('APP_URL') . env('API_BASE_PATH') . '/job',
        'BANK_DETAILS' => env('APP_URL') . env('API_BASE_PATH') . '/bankDetails',
        'EXPERIENCE' => env('APP_URL') . env('API_BASE_PATH') . '/experience',
        'EXPERIENCE_LIST' => env('APP_URL') . env('API_BASE_PATH') . '/experience/list',
        'EXPERIENCE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/experience/view',
        'EXPERIENCE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/experience/delete',
        'EDUCATION' => env('APP_URL') . env('API_BASE_PATH') . '/education',
        'DOCUMENTS' => env('APP_URL') . env('API_BASE_PATH') . '/documents',
        'ADDRESS' => env('APP_URL') . env('API_BASE_PATH') . '/address',
        'VEHICLE_LIST' => env('APP_URL') . env('API_BASE_PATH') . '/vehicle/list',
        'VEHICLE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/vehicle/view',
        'VEHICLE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/vehicle/delete',
        'VEHICLE' => env('APP_URL') . env('API_BASE_PATH') . '/vehicle',
        'P_IMG' => env('APP_URL') . env('API_BASE_PATH') . '/profile/img',
        'PAYMENT_MODE' => env('APP_URL') . env('API_BASE_PATH') . '/payment/mode',
    ],

    'PROPOSAL_URLS' => [
        'PROPOSAL_LIST' => env('APP_URL') . env('API_BASE_PATH') . '/applications/list',
        'PROPOSAL' => env('APP_URL') . env('API_BASE_PATH') . '/applications/create',
        'PROPOSAL_DOCUMENTS_LIST' => env('APP_URL') . env('API_BASE_PATH') . '/applications/documents/list',
        'PROPOSAL_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/applications/delete',
        'PROPOSAL_DOWNLOAD_DOCUMENT' => env('APP_URL') . env('API_BASE_PATH') . '/applications/download/document',
    ],


    'API_URLS' => [
        'ROLES' => env('APP_URL') . env('API_BASE_PATH') . '/roles',
        'ROLES_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/roles/view',
        'ROLES_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/roles/store',
        'ROLES_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/roles/update',
        'ROLES_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/roles/delete',
        'ROLES_PERMISSIONS' => env('APP_URL') . env('API_BASE_PATH') . '/role-permissions',
        'PERMISSIONS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/role-permissions/view',
    ],
    'USER_API_URLS' => [
        'USER' => env('APP_URL') . env('API_BASE_PATH') . '/user',
        'USER_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/user/view',
        'USER_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/user/store',
        'USER_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/user/update',
        'USER_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/user/delete',
    ],
    'MENU_API_URLS' => [
        'MENU' => env('APP_URL') . env('API_BASE_PATH') . '/menu',
        'MENU_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/menu/view',
        'MENU_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/menu/store',
        'MENU_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/menu/update',
        'MENU_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/menu/delete',
        'MENU_REORDER' => env('APP_URL') . env('API_BASE_PATH') . '/menu/reorder',
    ],

    'MENU_PERMISSION' => [
        'MENU_PERMISSION' => env('APP_URL') . env('API_BASE_PATH') . '/menu/permission',
        'GET_MENU' => env('APP_URL') . env('API_BASE_PATH') . '/getMenu',

    ],

    'EMPLOYEE_LEAVE_URLS' => [
        'EMPLOYEE_LEAVE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave',
        'EMPLOYEE_LEAVE_TYPE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/type',
        'EMPLOYEE_LEAVE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/view',
        'EMPLOYEE_LEAVE_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/store',
        'EMPLOYEE_LEAVE_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/update',
        'EMPLOYEE_LEAVE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/delete',
    ],

    'ADMIN_LEAVE_URLS' => [
        'ADMIN_LEAVE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/leaves',
        'ADMIN_LEAVE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/view',
        'ADMIN_LEAVE_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/Leave/store',
        'ADMIN_LEAVE_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Leave/update',
        'ADMIN_LEAVE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/leaves/request/delete',
        'ADMIN_LEAVE_HEDERDATA' => env('APP_URL') . env('API_BASE_PATH') . '/admin/leaves/headerData',
        'ALL_LEAVE_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/admin/getAllLeaveData',
    ],

    'ADMIN_LEAVE_TYPE_URLS' => [
        'ADMIN_LEAVE_TYPE_SETTING' => env('APP_URL') . env('API_BASE_PATH') . '/leaves/setting',
        'ADMIN_LEAVE_TYPE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Leave/type/view',
        'ADMIN_LEAVE_TYPE_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Leave/type/create',
        'ADMIN_LEAVE_TYPE_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Leave/type/update',
        'ADMIN_LEAVE_TYPE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Leave/type/delete',
    ],

    'ADMIN_LEAVE_REPORT_URLS' => [
        'LEAVE_REPORT' => env('APP_URL') . env('API_BASE_PATH') . '/leaves/report/get-data',
        'LEAVE_REPORT_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/leaves/report/update-data',
    ],

    'ADMIN_ATTENDANCE_URLS' => [
        'ADMIN_ATTENDANCE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Attendance',
        'ADMIN_ATTENDANCE_REQUEST' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Attendance/request',
        'ADMIN_ATTENDANCE_REQUEST_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Attendance/get-requestData',
        'ADMIN_ATTENDANCE_REQUEST_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Attendance/request/update',
        'ADMIN_ATTENDANCE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/attendance/request/delete',
        'ADMIN_ATTENDANCE_FILTER' => env('APP_URL') . env('API_BASE_PATH') . '/admin/AttendanceFilter',
        'ADMIN_ATTENDANCE_EXPORT' => env('APP_URL') . env('API_BASE_PATH') . '/admin/Attendance/export',
    ],

    'EMPLOYEE_ATTENDANCE_URLS' => [
        'EMPLOYEE_ATTENDANCE_REQUEST' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/attendance/request',
    ],

    'EMPLOYEE_ATTENDANCE_LIST' => [
        'EMPLOYEE_ATTENDANCE_LIST' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/list',
    ],

    'EMPLOYEE_DASHBOARD' => [
        'EMPLOYEE_DASHBOARD' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard',
        'LAST_CHECKOUT_RECORD' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/last-checkout-record',
        'GET_TIME_SHEET' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-TimeSheet',
        'GET_TODAY_ACTIVITY' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-TodayActivity',
        'GET_LEAVES_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-LeavesData',
        'GET_WORKING_HOURS_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-WorkingHoursData',
        'GET_PROGRESS_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-progressData',
        'GET_HOLIDAY_DATA' => env('APP_URL') . env('API_BASE_PATH') . '/employeeDashboard/get-upcomingHolidaysData',
    ],

    'ADMIN_HOLIDAY_URLS' => [
        'HOLIDAY' => env('APP_URL') . env('API_BASE_PATH') . '/holidays',
        'HOLIDAY_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/holidays/create',
        'HOLIDAY_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/admin/holidays/view',
        'HOLIDAY_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/holidays/update',
        'HOLIDAY_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/admin/holidays/delete',

    ],

    'PROJECTS_URLS' => [
        'PROJECTS' => env('APP_URL') . env('API_BASE_PATH') . '/projects',
        'PROJECTS_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/projects/create',
        'PROJECTS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/projects/view',
        'PROJECTS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/projects/update',
        'PROJECTS_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/projects/delete',
        'PROJECTS_UPLOAD_DOCUMENT' => env('APP_URL') . env('API_BASE_PATH') . '/projects/documents/upload',
        'PROJECTS_DELETE_DOCUMENT' => env('APP_URL') . env('API_BASE_PATH') . '/projects/documents/delete',
        'PROJECTS_DELETE_TASK' => env('APP_URL') . env('API_BASE_PATH') . '/projects/tasks/delete',
        'PROJECTS_GET_TEAM_MEMBERS' => env('APP_URL') . env('API_BASE_PATH') . '/projects/Get-teamMembers',

    ],

    'TASKS_URLS' => [
        'TASKS' => env('APP_URL') . env('API_BASE_PATH') . '/task',
        'TASKS_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/task/create',
        'TASKS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/task/view',
        'TASKS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/task/update',
        'TASKS_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/task/delete',
        'TASKS_STATUS' => env('APP_URL') . env('API_BASE_PATH') . '/task/status',
        'TASKS_COMMENT' => env('APP_URL') . env('API_BASE_PATH') . '/tasks/storeComment',
        'TASKS_GET_COMMENT' => env('APP_URL') . env('API_BASE_PATH') . '/get/tasks/Comment',
        'TASKS_STATUS_LOG' => env('APP_URL') . env('API_BASE_PATH') . '/task/status/log',
    ],

    'KANBAN_URLS' => [
        'KANBAN_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/kanban/view',
        'KANBAN_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/kanban/update',
        'KANBAN_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/kanban/delete',
    ],
    'NOTIFICATION_URLS' => [
        'NOTIFICATION' => env('APP_URL') . env('API_BASE_PATH') . '/notification',
        'NOTIFICATION_READ' => env('APP_URL') . env('API_BASE_PATH') . '/notification/markAsRead',
        'NOTIFICATION_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/notification/delete',
    ],

    'YEARS_URLS' => [
        'YEARS' => env('APP_URL') . env('API_BASE_PATH') . '/set-year-filter',
        'COMPANIES' => env('APP_URL') . env('API_BASE_PATH') . '/set-company-filter',
    ],
    'FINANCIAL_YEAR_URLS' => [
        'FINANCIAL_YEAR' => env('APP_URL') . env('API_BASE_PATH') . '/financial-year',
        'FINANCIAL_YEAR_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/financial-year/create',
        'FINANCIAL_YEAR_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/financial-year/view',
        'FINANCIAL_YEAR_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/financial-year/update',
        'FINANCIAL_YEAR_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/financial-year/delete',
    ],

    'COMPANY_URLS' => [
        'COMPANY' => env('APP_URL') . env('API_BASE_PATH') . '/company',
        'COMPANY_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/company/create',
        'COMPANY_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/company/view',
        'COMPANY_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/company/update',
        'COMPANY_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/company/delete',
    ],


    'EMPLOYEE_URLS' => [
        'EMPLOYEE' => env('APP_URL') . env('API_BASE_PATH') . '/employees',
    ],

    'ADMIN_DASHBOARD' => [
        'ADMIN_DEPARTMENT_EMPLOYEE_COUNT' => env('APP_URL') . env('API_BASE_PATH') . '/admin/dashboard/get-employee-department-count',
        'EMPLOYEE_ATTENDANCE_OVERVIEW' => env('APP_URL') . env('API_BASE_PATH') . '/admin/dashboard/get-employee-attendance-overview',
    ],
    'SUPER_ADMIN_DASHBOARD' => [
        'COMPANY_STATUS_OVERVIEW' => env('APP_URL') . env('API_BASE_PATH') . '/super-admin/dashboard/company-status-overview',
    ],
    'ALLOWANCE_URLS' => [
        'ALLOWANCE' => env('APP_URL') . env('API_BASE_PATH') . '/allowance',
        'ALLOWANCE_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/allowance/create',
        'ALLOWANCE_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/allowance/view',
        'ALLOWANCE_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/allowance/update',
        'ALLOWANCE_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/allowance/delete',
    ],

    'DEDUCTION_URLS' => [
        'DEDUCTION' => env('APP_URL') . env('API_BASE_PATH') . '/deduction',
        'DEDUCTION_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/deduction/create',
        'DEDUCTION_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/deduction/view',
        'DEDUCTION_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/deduction/update',
        'DEDUCTION_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/deduction/delete',
    ],

    'EMPLOYEE_SALARY_URLS' => [
        'EMPLOYEE_SALARY' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary',
        'EMPLOYEE_SALARY_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary/create',
        'EMPLOYEE_SALARY_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary/view',
        'EMPLOYEE_SALARY_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary/update',
        'EMPLOYEE_SALARY_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary/delete',
        'EMPLOYEE_SALARY_DOWNLOAD' => env('APP_URL') . env('API_BASE_PATH') . '/employee/salary/download',
    ],

    'EMAIL_SETTINGS_URLS' => [
        'EMAIL_SETTINGS' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings',
        'EMAIL_SETTINGS_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings/create',
        'EMAIL_SETTINGS_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings/view',
        'EMAIL_SETTINGS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings/update',
        'EMAIL_SETTINGS_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings/delete',
        'EMAIL_SETTINGS_TEST_CONNECTION' => env('APP_URL') . env('API_BASE_PATH') . '/email-settings/test-connection',
    ],

    'DEPARTMENT_URLS' => [
        'DEPARTMENT' => env('APP_URL') . env('API_BASE_PATH') . '/department',
        'DEPARTMENT_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/department/create',
        'DEPARTMENT_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/department/view',
        'DEPARTMENT_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/department/update',
        'DEPARTMENT_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/department/delete',
    ],

    'DESIGNATION_URLS' => [
        'DESIGNATION' => env('APP_URL') . env('API_BASE_PATH') . '/designation',
        'DESIGNATION_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/designation/create',
        'DESIGNATION_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/designation/view',
        'DESIGNATION_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/designation/update',
        'DESIGNATION_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/designation/delete',
    ],

    'SHIFT_URLS' => [
        'SHIFT' => env('APP_URL') . env('API_BASE_PATH') . '/shift',
        'SHIFT_STORE' => env('APP_URL') . env('API_BASE_PATH') . '/shift/create',
        'SHIFT_VIEW' => env('APP_URL') . env('API_BASE_PATH') . '/shift/view',
        'SHIFT_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/shift/update',
        'SHIFT_DELETE' => env('APP_URL') . env('API_BASE_PATH') . '/shift/delete',
    ],

    'NOTIFICATION_SETTINGS_URLS' => [
        'NOTIFICATION_SETTINGS' => env('APP_URL') . env('API_BASE_PATH') . '/notification-settings',
        'NOTIFICATION_SETTINGS_UPDATE' => env('APP_URL') . env('API_BASE_PATH') . '/notification-settings/update',
    ],

];

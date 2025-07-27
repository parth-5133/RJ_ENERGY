<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCompanyRequest;
use App\Helpers\JWTUtils;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\NotificationSetting;

class CompanyController extends Controller
{
    public function index()
    {
        $financialYears = DB::table('companies')
            ->leftJoin('users', 'companies.updated_by', '=', 'users.id')
            ->leftJoin('countries', 'companies.country', '=', 'countries.id')
            ->leftJoin('states', 'companies.state', '=', 'states.id')
            ->select(
                'companies.id',
                'companies.legal_name',
                'companies.phone',
                'companies.alternate_mobile_no',
                'companies.email',
                'companies.gst_number',
                'companies.pan_number',
                'companies.logo_url',
                'companies.website',
                'companies.address_line_1',
                'companies.address_line_2',
                'companies.address_line_3',
                'companies.city',
                'companies.pin_code',
                'companies.is_active',
                'countries.name as country_name',
                'states.name as state_name',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(companies.updated_at, '%d/%m/%Y') as updated_at")
            )
            ->where('companies.deleted_at', "=", null)
            ->get();

        return ApiResponse::success($financialYears, ResMessages::RETRIEVED_SUCCESS);
    }
    public function createCompany(CreateCompanyRequest $request)
    {
        DB::beginTransaction();

        try {
            $currentUser = JWTUtils::getCurrentUserByUuid();
            $Data = $request->all();

            if ($request->hasFile('logo_url')) {
                $file = $request->file('logo_url');
                $filename = time() . '.' . $file->getClientOriginalExtension();

                $folderPath = 'public/Company_logo';
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath);
                }

                $file->storeAs($folderPath, $filename);

                $Data['logo_url'] = 'storage/Company_logo/' . $filename;
                $Data['logo_display_name'] = $filename;
            }

            $Data['created_by'] = $currentUser->id;
            $Data['updated_by'] = null;
            $Data['created_at'] = now();
            $Data['updated_at'] = null;

            $company = Company::create($Data);

            if (!$company) {
                throw new \Exception('Company creation failed');
            }

            $baseRoles = config('role_constants');
            $roles = [];

            foreach ($baseRoles as $role) {
                $roles[] = [
                    'company_id' => $company->id,
                    'name' => $role['name'],
                    'code' => $role['code'],
                    'access_level' => $role['access_level'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => null,
                    'created_by' => $currentUser->id,
                    'updated_by' => null,
                ];
            }

            Role::insert($roles);

            // Clone Menus
            $menus = Menu::whereNull('deleted_at')
                ->where('company_id', env('APP_BASE_COMPANYID'))
                ->get();

            $clonedMenus = [];
            $oldToNewIdMap = [];

            foreach ($menus as $menu) {
                $clonedMenus[] = [
                    'name' => $menu->name,
                    'access_code' => $menu->access_code,
                    'navigation_url' => $menu->navigation_url,
                    'display_in_menu' => $menu->display_in_menu,
                    'parent_menu_id' => null,
                    'menu_icon' => $menu->menu_icon,
                    'menu_class' => $menu->menu_class,
                    'display_order' => $menu->display_order,
                    'company_id' => $company->id,
                    'created_by' => $currentUser->id,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => null,
                    'is_active' => 1,
                    '_old_id' => $menu->id
                ];
            }

            foreach ($clonedMenus as $cloned) {
                $oldId = $cloned['_old_id'];
                unset($cloned['_old_id']);

                $newMenu = Menu::create($cloned);
                $oldToNewIdMap[$oldId] = $newMenu->id;
            }

            foreach ($menus as $originalMenu) {
                if ($originalMenu->parent_menu_id) {
                    $newMenuId = $oldToNewIdMap[$originalMenu->id] ?? null;
                    $newParentId = $oldToNewIdMap[$originalMenu->parent_menu_id] ?? null;

                    if ($newMenuId && $newParentId) {
                        Menu::where('id', $newMenuId)->update(['parent_menu_id' => $newParentId]);
                    }
                }
            }

            $roleId = DB::table('roles')->where('code', $this->AdminRoleCode)->where('company_id', $company->id)->value('id');
            $menuIds = DB::table('menus')->where('company_id', $company->id)->pluck('id');

            $data = [];
            $timestamp = now();

            foreach ($menuIds as $menuId) {
                $data[] = [
                    'menu_id' => $menuId,
                    'role_id' => $roleId,
                    'canAdd' => true,
                    'canView' => true,
                    'canEdit' => true,
                    'canDelete' => true,
                    'created_at' => $timestamp
                ];
            }

            DB::table('role_permission_mapping')->insert($data);

            $userData = [
                'first_name' => $company->legal_name,
                'last_name' => '.',
                'email' => $company->email,
                'employee_id' => 0,
                'password' => bcrypt($company->phone),
                'role_id' => $roleId,
                'company_id' => $company->id,
                'created_by' => $currentUser->id,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => null,
                'ip_address' => $request->ip(),
                'is_active' => 1,
            ];

            $user = User::create($userData);
            if (!$user) {
                throw new \Exception('User creation failed');
            }

            $types = [
                'leave_request',
                'attendance_request',
                'task_assignment',
                'task_status_update',
            ];

            $notificationSettings = [];
            foreach ($types as $type) {
                $notificationSettings[] = [
                    'company_id' => $company->id,
                    'type' => $type,
                    'email_enabled' => true,
                    'browser_enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $notificationSettings = NotificationSetting::insert($notificationSettings);

            if (!$notificationSettings) {
                throw new \Exception('Notification settings creation failed');
            }

            $currentYear = \Carbon\Carbon::now()->year;
            $startOfYear = \Carbon\Carbon::create($currentYear, 1, 1)->startOfYear()->toDateString();
            $endOfYear = \Carbon\Carbon::create($currentYear, 12, 31)->endOfDay()->toDateString();

            $financialData = [
                'company_id' => $company->id,
                'from_date' => $startOfYear,
                'to_date' =>  $endOfYear,
                'display_year' => $currentYear,
                'is_currentYear' => true,
                'is_active' => true,
                'created_by' => $currentUser->id,
                'created_at' => now(),
                'updated_at' => null,
                'updated_by' => $currentUser->id,
            ];

            $financialYear = FinancialYear::create($financialData);
            if (!$financialYear) {
                throw new \Exception('Financial year creation failed');
            }

            DB::commit();
            return ApiResponse::success($company, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollback();
            return ApiResponse::error(['message' => $e->getMessage()], ResMessages::UNPROCESSABLE_ENTITY);
        }
    }
    public function viewCompany(Request $request)
    {
        $companyId = $request->companyId;

        $data = Company::find($companyId);
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function updateCompany(CreateCompanyRequest $request)
    {
        $companyId = $request->companyId;

        $data = Company::find($companyId);

        if (!$data) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data->fill($request->validated());

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $folderPath = 'public/Company_logo';
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            $file->storeAs($folderPath, $filename);

            $data['logo_url'] = 'storage/Company_logo/' . $filename;
            $data['logo_display_name'] = $filename;
        }

        $data->updated_by = $currentUser->id;
        $data->updated_at = now();

        $data->save();

        return ApiResponse::success($data,  ResMessages::UPDATED_SUCCESS);
    }
    public function deleteCompany($id)
    {
        $data = Company::find($id);

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sequence;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Notification;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUpdateUserRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Constants\ResMessages;
use Illuminate\Http\Request;
use App\Helpers\FinancialYearService;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $role_id = $request->query('role_id');
        $companiesId = GetCompanyId::GetCompanyId();

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $currentAccessLevel = AccessLevel::getAccessLevel();

        $usersQuery = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('users as updater', 'users.updated_by', '=', 'updater.id')
            ->select(
                'users.id',
                'users.uuid',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.middle_name, ''), ' ', users.last_name) as name"),
                'users.email',
                'users.is_active',
                'roles.name as role_name',
                'roles.code as role_code',
                'users.employee_id',
                DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(users.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->where('users.id', '!=', $userId)
            ->where('roles.access_level', '<=', $currentAccessLevel->access_level)
            ->where('roles.code',  $this->employeeRoleCode)
            ->whereNull('users.deleted_at');

        if ($companiesId) {
            $usersQuery->where('users.company_id', $companiesId);
        }

        if (!is_null($role_id)) {
            $usersQuery->where('users.role_id', $role_id);
        }

        $users = $usersQuery->orderByDesc('id')->get();

        return ApiResponse::success($users, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreUpdateUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $currentUser = JWTUtils::getCurrentUserByUuid();
            $financialYears = FinancialYearService::getCurrentFinancialYear();
            $CompanyId = GetCompanyId::GetCompanyId();
            $userData = $request->all();

            if ($CompanyId == null) {
                return ApiResponse::error(ResMessages::NOT_FOUND, 404);
            }

            $sequenceNo = Sequence::where('type', 'EmployeeID')->value('sequenceNo');
            $newSequenceNo = ($sequenceNo !== null) ? $sequenceNo + 1 : 1;
            $employeeId = str_pad($newSequenceNo, 4, '0', STR_PAD_LEFT);

            // Prepare user data with additional fields
            $userData['employee_id'] = $employeeId;
            $userData['created_by'] = $currentUser->id;
            $userData['created_at'] = now();
            $userData['updated_at'] = null;
            $userData['ip_address'] = $request->ip();
            $userData['last_logged_in_at'] = now();
            $userData['company_id'] = $CompanyId;

            if ($request->has('role_id')) {
                $userData['role_id'] = $request->role_id;
            }

            $user = User::create($userData);

            Sequence::where('type', 'EmployeeID')->update(['sequenceNo' => $newSequenceNo]);

            $leaveTypes = LeaveType::all();
            if ($leaveTypes->isNotEmpty()) {
                $currentYear = now()->year;

                foreach ($leaveTypes as $leaveType) {
                    LeaveBalance::create([
                        'employee_id' => $user->id,
                        'company_id' => $user->company_id,
                        'financialYear_id' => $financialYears->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $currentYear,
                        'balance' => $leaveType->max_days,
                        'carry_forwarded' => 0,
                    ]);
                }
            }

            DB::commit();
            return ApiResponse::success($user, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
    public function view(Request $request)
    {
        $userId = $request->userId;

        $user = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id') // Directly join roles using role_id in users table
            ->select(
                'users.*',
                'roles.id as role_id',
                'roles.name as role_name' // Fetch role name from roles table
            )
            ->where('users.id', $userId)
            ->first();

        if ($user) {
            return ApiResponse::success($user, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreUpdateUserRequest $request)
    {
        $userId = $request->userId;

        $user = User::find($userId);

        if (!$user) {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        // Update user details
        $user->fill($request->validated());
        $user->updated_by = $currentUser->id;
        $user->updated_at = now();

        // If password is being updated, hash it
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Update the role_id directly in the users table
        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        // Save the updated user
        $user->save();

        return ApiResponse::success($user, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return ApiResponse::success($user, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($user, ResMessages::NOT_FOUND);
        }
    }
    public function showNotifications()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $notifications = Notification::where('deleted_at', null)
            ->where('user_id', $currentUser->id)
            ->orderByDesc('id')
            ->get();

        return ApiResponse::success($notifications, ResMessages::RETRIEVED_SUCCESS);
    }
    public function markAllAsRead(Request $request)
    {
        $id = $request->input('id');

        if (!empty($id) && $id != 0) {
            $notification = Notification::find($id);

            if ($notification) {
                $notification->read = true;
                $notification->save();
                return ApiResponse::success($notification, ResMessages::UPDATED_SUCCESS, 1);
            }

            return ApiResponse::error(ResMessages::NOT_FOUND);
        }

        $currentUser = JWTUtils::getCurrentUserByUuid();

        $notifications = Notification::where('user_id', $currentUser->id)
            ->where('read', false)
            ->update(['read' => true]);

        return ApiResponse::success($notifications, ResMessages::RETRIEVED_SUCCESS);
    }
    public function deleteNotification($id)
    {
        if ($id == "0") {
            $currentUser = JWTUtils::getCurrentUserByUuid();
            $userId = $currentUser->id;

            $deleted = Notification::where('user_id', $userId)->delete();

            if ($deleted) {
                return ApiResponse::success([], ResMessages::DELETED_SUCCESS, 11);
            } else {
                return ApiResponse::error(ResMessages::NOT_FOUND);
            }
        }
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return ApiResponse::success($notification, ResMessages::DELETED_SUCCESS, 10);
        } else {
            return ApiResponse::error(ResMessages::NOT_FOUND);
        }
    }
}

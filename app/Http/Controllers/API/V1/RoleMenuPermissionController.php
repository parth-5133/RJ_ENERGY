<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermissionRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\RolePermissionMapping;
use App\Constants\ResMessages;


class RoleMenuPermissionController extends Controller
{
    public function store(RolePermissionRequest $request)
    {
        // Extract the first permission to get the role_id
        $firstPermission = $request->input('0');
        $roleId = $firstPermission['role_id'];

        // Prepare to insert or update new permissions
        $permissions = $request->all();
        $processedPermissions = [];

        // Loop through each permission in the request
        foreach ($permissions as $permission) {
            // Check if the permission for this role_id and menu_id already exists
            $existingPermission = DB::table('role_permission_mapping')
                ->where('role_id', $permission['role_id'])
                ->where('menu_id', $permission['menu_id'])
                ->first();

            if ($existingPermission) {
                // If permission exists, update it
                DB::table('role_permission_mapping')
                    ->where('id', $existingPermission->id)
                    ->update([
                        'canAdd' => $permission['canAdd'],
                        'canView' => $permission['canView'],
                        'canEdit' => $permission['canEdit'],
                        'canDelete' => $permission['canDelete'],
                        'updated_at' => now(),
                    ]);

                // Add to processed permissions
                $processedPermissions[] = $existingPermission->id;
            } else {
                // If permission doesn't exist, insert a new one
                $newPermissionId = DB::table('role_permission_mapping')->insertGetId([
                    'menu_id' => $permission['menu_id'],
                    'role_id' => $permission['role_id'],
                    'canAdd' => $permission['canAdd'],
                    'canView' => $permission['canView'],
                    'canEdit' => $permission['canEdit'],
                    'canDelete' => $permission['canDelete'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Add to processed permissions
                $processedPermissions[] = $newPermissionId;
            }
        }

        // Return success response with processed permission IDs
        return ApiResponse::success($processedPermissions, 'Permissions assigned/updated successfully.');
    }
    public function view($id)
    {
        $data = RolePermissionMapping::select('role_permission_mapping.*', 'roles.name as role_name')
            ->join('roles', 'roles.id', '=', 'role_permission_mapping.role_id') // Join the roles table
            ->where('role_permission_mapping.role_id', $id)
            ->get();
        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permission_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus', 'id');
            $table->foreignId('role_id')->constrained('roles', 'id');
            $table->boolean('canAdd')->default(false);
            $table->boolean('canView')->default(false);
            $table->boolean('canEdit')->default(false);
            $table->boolean('canDelete')->default(false);
            $table->timestamps();
        });

        // SUPERADMIN full access
        $roleId = DB::table('roles')->where('code', config('roles.SUPERADMIN'))->value('id');
        $menuIds = DB::table('menus')->pluck('id');
        $timestamp = now();

        $data = [];
        foreach ($menuIds as $menuId) {
            $data[] = [
                'menu_id' => $menuId,
                'role_id' => $roleId,
                'canAdd' => true,
                'canView' => true,
                'canEdit' => true,
                'canDelete' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        DB::table('role_permission_mapping')->insert($data);

        // CLIENT limited access
        $roleId = DB::table('roles')->where('code', config('roles.CLIENT'))->value('id');
        $menuIds = DB::table('menus')->whereIn('access_code', ['PROJECTS', 'KANBAN'])->pluck('id');

        $ClientData = [];
        foreach ($menuIds as $menuId) {
            $ClientData[] = [
                'menu_id' => $menuId,
                'role_id' => $roleId,
                'canAdd' => true,
                'canView' => true,
                'canEdit' => true,
                'canDelete' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }
        DB::table('role_permission_mapping')->insert($ClientData);

        $roleId = DB::table('roles')->where('code', config('roles.EMPLOYEE'))->value('id');
        $menuIds = DB::table('menus')->whereIn('access_code', ['DASHBOARD', 'LEAVESREQUEST', 'ATTENDANCEREQUEST'])->pluck('id');

        $employeeData = [];
        foreach ($menuIds as $menuId) {
            $ClientData[] = [
                'menu_id' => $menuId,
                'role_id' => $roleId,
                'canAdd' => true,
                'canView' => true,
                'canEdit' => true,
                'canDelete' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }
        DB::table('role_permission_mapping')->insert($employeeData);
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission_mapping');
    }
};

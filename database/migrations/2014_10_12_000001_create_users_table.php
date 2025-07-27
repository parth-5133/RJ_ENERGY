<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('role_id')->nullable()->constrained('roles', 'id');
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('employee_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken()->nullable();
            $table->string('ip_address')->nullable();
            $table->dateTime('last_password_updated_at')->nullable();
            $table->dateTime('last_logged_in_at')->nullable();
            $table->softDeletes();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Retrieve the role ID dynamically for "System Admin"
        $systemAdminRoleId = DB::table('roles')->where('code', config('roles.ADMIN'))->value('id');
        $systemSuperAdminRoleId = DB::table('roles')->where('code', config('roles.SUPERADMIN'))->value('id');
        $users = [
            [
                'uuid' => Str::uuid(),
                'company_id' => env("APP_BASE_COMPANYID"),
                'role_id' => $systemSuperAdminRoleId,
                'employee_id' => '0',
                'first_name' => 'Super',
                'middle_name' => null,
                'last_name' => 'Admin',
                'email' => 'sadmin@skysphereinfosoft.com',
                'email_verified_at' => now(),
                'password' => Hash::make('S@dmin2024'),
                'ip_address' => null,
                'last_password_updated_at' => null,
                'last_logged_in_at' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'uuid' => Str::uuid(),
                'company_id' => env("APP_BASE_COMPANYID"),
                'role_id' => $systemAdminRoleId,
                'employee_id' => '1',
                'first_name' => 'Rohit',
                'middle_name' => null,
                'last_name' => 'Kumar',
                'email' => 'rohit@skysphereinfosoft.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Rohit2024'),
                'ip_address' => null,
                'last_password_updated_at' => null,
                'last_logged_in_at' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'uuid' => Str::uuid(),
                'company_id' => env("APP_BASE_COMPANYID"),
                'role_id' => $systemAdminRoleId,
                'employee_id' => '2',
                'first_name' => 'Hitesh',
                'middle_name' => null,
                'last_name' => 'Kumar',
                'email' => 'hitesh@skysphereinfosoft.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Hitesh2024'),
                'ip_address' => null,
                'last_password_updated_at' => null,
                'last_logged_in_at' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => null,
                'updated_by' => null,
            ]
        ];

        // Insert all users in a single query
        DB::table('users')->insert($users);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

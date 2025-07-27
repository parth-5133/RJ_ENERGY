<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('name');
            $table->string('code');
            $table->integer('access_level');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });

        DB::table('roles')->insert([
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Super Admin',
                'code' => config('roles.SUPERADMIN'),
                'access_level' => '1000',
                'created_at' => now(),
                'created_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Admin',
                'code' => config('roles.ADMIN'),
                'access_level' => '100',
                'created_at' => now(),
                'created_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Employee',
                'code' => config('roles.EMPLOYEE'),
                'access_level' => '50',
                'created_at' => now(),
                'created_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Client',
                'code' => config('roles.CLIENT'),
                'access_level' => '50',
                'created_at' => now(),
                'created_by' => null,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

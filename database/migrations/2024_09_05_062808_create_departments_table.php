<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies', 'id')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
        // Seed initial data
        DB::table('departments')->insert([
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Human Resources',
                'is_active' => true,
                'created_at' => now(),
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Finance',
                'is_active' => true,
                'created_at' => now(),
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Software Engineer',
                'is_active' => true,
                'created_at' => now(),
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'Database Administrator',
                'is_active' => true,
                'created_at' => now(),
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'company_id' => env("APP_BASE_COMPANYID"),
                'name' => 'UI/UX Designer',
                'is_active' => true,
                'created_at' => now(),
                'created_by' => null,
                'updated_by' => null,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};

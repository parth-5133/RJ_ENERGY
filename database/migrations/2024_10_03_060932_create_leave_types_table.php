<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Add this for inserting data


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('leave_type_name', 50);
            $table->integer('max_days');
            $table->integer('carry_forward_max_balance')->default(0)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_currentYear')->default(true);
            $table->integer('financialYear_id')->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        DB::table('leave_types')->insert([
            ['leave_type_name' => 'Paid Leave', 'max_days' => 12, 'is_currentYear' => true, 'company_id' => env('APP_BASE_COMPANYID'), 'financialYear_id' => env('APP_BASE_FINACIAL_YEAR'), 'created_at' => now()],
            ['leave_type_name' => 'Maternity Leave', 'max_days' => 0, 'is_currentYear' => true, 'company_id' => env('APP_BASE_COMPANYID'), 'financialYear_id' => env('APP_BASE_FINACIAL_YEAR'), 'created_at' => now()],
            ['leave_type_name' => 'Paternity Leave', 'max_days' => 0, 'is_currentYear' => true, 'company_id' => env('APP_BASE_COMPANYID'), 'financialYear_id' => env('APP_BASE_FINACIAL_YEAR'), 'created_at' => now()],
            ['leave_type_name' => 'Leave without Pay', 'max_days' => 0, 'is_currentYear' => true, 'company_id' => env('APP_BASE_COMPANYID'), 'financialYear_id' => env('APP_BASE_FINACIAL_YEAR'), 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};

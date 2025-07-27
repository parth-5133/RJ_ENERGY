<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('department_id');
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->integer('financialYear_id')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0.00);
            $table->decimal('total_allowances', 10, 2)->default(0.00);
            $table->decimal('total_deductions', 10, 2)->default(0.00);
            $table->decimal('total_salary', 10, 2)->default(0.00);
            $table->string('salary_month');
            $table->string('salary_year');
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};

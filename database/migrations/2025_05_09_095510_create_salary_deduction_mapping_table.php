<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_deduction_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_salary_id');
            $table->unsignedBigInteger('deduction_id');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('employee_salary_id')->references('id')->on('employee_salaries')->onDelete('cascade');
            $table->foreign('deduction_id')->references('id')->on('employee_deductions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_deduction_mapping');
    }
};

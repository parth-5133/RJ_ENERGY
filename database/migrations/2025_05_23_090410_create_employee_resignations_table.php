<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->foreignId('employee_id')->constrained('users', 'id');
            $table->date('resignation_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['Approved', 'Rejected', 'Cancelled', 'Pending'])->default('Pending');
            $table->date('last_working_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_resignations');
    }
};

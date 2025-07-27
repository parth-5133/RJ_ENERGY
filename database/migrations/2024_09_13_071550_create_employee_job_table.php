<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('job_title');
            $table->foreignId('department')->constrained('departments', 'id');
            $table->string('location');
            $table->foreignId('employee_type')->constrained('employee_types', 'id');
            $table->date('date_of_joining');
            $table->foreignId('employee_status')->constrained('employee_statuses', 'id');
            $table->foreignId('reporting_id')->constrained('users'); // Assuming users table
            $table->foreignId('designation')->constrained('designations', 'id');
            $table->string('work_schedule')->nullable();
            $table->text('job_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_jobs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendance_requests')) {
            Schema::create('attendance_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
                $table->foreignId('employee_id')->constrained('users', 'id');
                $table->string('attendance_status');
                $table->date('attendance_date');
                $table->time('attendance_time');
                $table->text('note')->nullable();
                $table->enum('status', ['Approved', 'Rejected', 'Cancelled', 'Pending'])->default('Pending');
                $table->text('comment')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users', 'id');
                $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};

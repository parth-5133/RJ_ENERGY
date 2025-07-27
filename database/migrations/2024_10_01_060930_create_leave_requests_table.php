<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->foreignId('employee_id')->constrained('users', 'id');
            $table->unsignedBigInteger('leave_type_id')->constrained('leave_types', 'id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('leave_session_id')->default(0);
            $table->float('total_days');
            $table->text('reason');
            $table->enum('status', ['Approved','Rejected','Cancelled','Pending'])->default('Pending');
            $table->date('request_date');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id');
            $table->date('approval_date')->default(now());
            $table->text('comments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};

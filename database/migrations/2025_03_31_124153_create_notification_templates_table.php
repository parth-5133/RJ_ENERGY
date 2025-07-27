<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->string('title');
            $table->text('message');
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        DB::table('notification_templates')->insert([
            [
                'template_name' => 'task_assigned',
                'title' => 'New Task Assigned',
                'message' => '{Create_by} assigned you to the task {task_title} on the project {project_name}.',
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => 1,
                'updated_by' => null,
            ],

            [
                'template_name' => 'leave_request_created',
                'title' => '{employee_name} submitted an Leave request',
                'message' => '{employee_name} has submitted a leave request for {leave_type_name}. Please review and take necessary action.',
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => 1,
                'updated_by' => null,
            ],
            [
                'template_name' => 'attendance_request_created',
                'title' => '{employee_name} submitted an attendance request',
                'message' => '{employee_name} has submitted a Check In attendance request. Please review and take necessary action.',
                'created_at' => now(),
                'updated_at' => null,
                'created_by' => 1,
                'updated_by' => null,
            ],

        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};

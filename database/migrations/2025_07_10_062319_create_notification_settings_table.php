<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('type');
            $table->boolean('email_enabled')->default(true);
            $table->boolean('browser_enabled')->default(true);
            $table->timestamps();
        });

        DB::table('notification_settings')->insert([
            [
                'company_id' => env('APP_BASE_COMPANYID'),
                'type' => 'leave_request',
                'email_enabled' => true,
                'browser_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => env('APP_BASE_COMPANYID'),
                'type' => 'attendance_request',
                'email_enabled' => true,
                'browser_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => env('APP_BASE_COMPANYID'),
                'type' => 'task_assignment',
                'email_enabled' => true,
                'browser_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => env('APP_BASE_COMPANYID'),
                'type' => 'task_status_update',
                'email_enabled' => true,
                'browser_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};

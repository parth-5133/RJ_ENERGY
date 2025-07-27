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
        Schema::create('employee_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->string('profile_image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->unsignedBigInteger('marital_status_id')->nullable()->constrained('employee_statuses', 'id');
            $table->unsignedBigInteger('nationality_id')->nullable()->constrained('countries', 'id');
            $table->string('religion')->nullable();
            $table->unsignedBigInteger('blood_group')->nullable()->constrained('bloodgroups', 'id');
            $table->string('citizenship')->nullable();
            $table->enum('disability_status', ['0', '1', '2', '3'])->default('0');
            $table->string('personal_email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->string('emergency_phone_number')->nullable();
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
        Schema::dropIfExists('employee_infos');
    }
};

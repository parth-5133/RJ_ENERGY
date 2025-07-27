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
        if (!Schema::hasTable('employee_experiences')) {
            Schema::create('employee_experiences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users', 'id');
                $table->string('organization_name');
                $table->date('from_date');
                $table->date('to_date');
                $table->string('designation');
                $table->foreignId('country')->constrained('countries', 'id');
                $table->foreignId('state')->constrained('states', 'id');
                $table->string('city');
                $table->string('experience_letter')->nullable();
                $table->string('file_display_name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->softDeletes();
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users', 'id');
                $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_experiences');
    }
};

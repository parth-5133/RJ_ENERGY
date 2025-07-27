<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->string('task_id');
            $table->string('title');
            $table->date('due_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedBigInteger('project_id')->constrained('projects', 'id');
            $table->string('status');
            $table->integer('priority');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

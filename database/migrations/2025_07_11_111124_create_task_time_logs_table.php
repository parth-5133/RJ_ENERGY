<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('task_time_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('from_column_id');
            $table->unsignedBigInteger('to_column_id');
            $table->unsignedBigInteger('moved_by');
            $table->dateTime('entered_start_time')->nullable();
            $table->dateTime('entered_end_time')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->boolean('is_manual')->default(false);
            $table->dateTime('moved_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_time_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('employee_id')->constrained('users', 'id');
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->date('check_out_date')->nullable();
            $table->enum('session_type', ['regular', 'break'])->default('regular');
            $table->enum('status', ['active', 'complete'])->default('active');
            $table->string('note')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('accuracy', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeStatusTable extends Migration
{
    public function up()
    {
        Schema::create('employee_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Seed data directly after creating the table
        DB::table('employee_statuses')->insert([
            ['name' => 'Active', 'is_active' => true],
            ['name' => 'On Leave', 'is_active' => true],
            ['name' => 'Terminated', 'is_active' => true],
            ['name' => 'Retired', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employee_statuses');
    }
}

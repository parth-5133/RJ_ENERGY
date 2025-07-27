<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeTypeTable extends Migration
{
    public function up()
    {
        Schema::create('employee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Seed data directly after creating the table
        DB::table('employee_types')->insert([
            ['name' => 'Full Time', 'is_active' => true],
            ['name' => 'Part Time', 'is_active' => true],
            ['name' => 'Contract', 'is_active' => true],
            ['name' => 'Intern', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employee_types');
    }
}

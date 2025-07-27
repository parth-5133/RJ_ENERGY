<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeGradeTable extends Migration
{
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Seed data directly after creating the table
        DB::table('designations')->insert([
            ['name' => 'Junior', 'is_active' => true],
            ['name' => 'Mid Level', 'is_active' => true],
            ['name' => 'Senior', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('designations');
    }
}

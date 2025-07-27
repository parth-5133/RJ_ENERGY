<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMaritalStatusTable extends Migration
{
    public function up()
    {
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Seed data directly after creating the table
        DB::table('marital_statuses')->insert([
            ['name' => 'Single', 'is_active' => true],
            ['name' => 'Married', 'is_active' => true],
            ['name' => 'Divorced', 'is_active' => true],
            ['name' => 'Remarried', 'is_active' => true],
            ['name' => 'Widowed', 'is_active' => true],
            ['name' => 'Separated', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('marital_statuses');
    }
}

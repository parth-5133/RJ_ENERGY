<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSequenceMasterTable extends Migration
{

    public function up()
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->integer('sequenceNo');
            $table->timestamps();
        });

        // Seed initial data
        DB::table('sequences')->insert([
            [
                'type' => 'EmployeeID',
                'prefix' => null,
                'suffix' => null,
                'sequenceNo' => 0,
                'created_at' => now(),
            ],
            [
                'type' => 'ProjectID',
                'prefix' => 'PRO',
                'suffix' => null,
                'sequenceNo' => 0,
                'created_at' => now(),
            ],
            [
                'type' => 'TaskID',
                'prefix' => 'T',
                'suffix' => null,
                'sequenceNo' => 0,
                'created_at' => now(),
            ],
        ]);
    }



    public function down()
    {
        Schema::dropIfExists('sequence_master');
    }
}

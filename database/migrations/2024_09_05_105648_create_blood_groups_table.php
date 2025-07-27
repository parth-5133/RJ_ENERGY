<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bloodgroups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        DB::table('bloodgroups')->insert([
            ['name' => 'A+'],
            ['name' => 'A-'],
            ['name' => 'B+'],
            ['name' => 'B-'],
            ['name' => 'O+'],
            ['name' => 'O-'],
            ['name' => 'AB+'],
            ['name' => 'AB-'],
            ['name' => 'Other'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bloodgroups');
    }
};

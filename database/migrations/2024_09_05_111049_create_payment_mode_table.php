<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePaymentModeTable extends Migration
{
    public function up()
    {
        Schema::create('payment_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->dateTime('updated_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Seed data directly after creating the table
        DB::table('payment_modes')->insert([
            ['name' => 'Direct Deposit', 'is_active' => true],
            ['name' => 'Check', 'is_active' => true],
            ['name' => 'Cash', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_modes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_bank_details', function (Blueprint $table) {
  $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bank_details');
    }
};

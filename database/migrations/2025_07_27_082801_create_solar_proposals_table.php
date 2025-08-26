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
        Schema::create('solar_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('application_id')->unique();
            $table->string('solar_capacity')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('roof_area')->nullable();
            $table->string('net_metering')->nullable();
            $table->string('subsidy_claimed')->nullable();
            $table->string('purchase_mode')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solar_proposals');
    }
};

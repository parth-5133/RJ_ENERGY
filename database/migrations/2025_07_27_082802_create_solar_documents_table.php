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
        Schema::create('solar_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->string('aadhaar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('electricity_bill')->nullable();
            $table->string('bank_proof')->nullable();
            $table->string('passport_photo')->nullable();
            $table->string('ownership_proof')->nullable();
            $table->string('site_photo')->nullable();
            $table->string('self_declaration')->nullable();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('solar_proposals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solar_documents');
    }
};

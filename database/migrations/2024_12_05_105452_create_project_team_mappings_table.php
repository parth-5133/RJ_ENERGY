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
        if (!Schema::hasTable('project_team_mappings')) {
            Schema::create('project_team_mappings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('team_type');
                $table->unsignedBigInteger('project_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_team_mappings');
    }
};

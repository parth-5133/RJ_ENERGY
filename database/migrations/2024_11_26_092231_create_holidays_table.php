<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('holiday_name', 50);
            $table->date('holiday_date');
            $table->string('day', 10);
            $table->boolean('is_active')->default(true);
            $table->integer('financialYear_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this for DB insert
use Carbon\Carbon; // Add this for date handling

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('display_year');
            $table->boolean('is_currentYear')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Add default current year record
        $currentYear = Carbon::now()->year;
        $from = Carbon::createFromDate($currentYear, 4, 1); // Example: starts April 1
        $to = Carbon::createFromDate($currentYear + 1, 3, 31); // Ends March 31 next year

        DB::table('financial_years')->insert([
            'company_id' => 1, // Use appropriate default or set to null
            'from_date' => $from,
            'to_date' => $to,
            'display_year' => $from->format('Y') . '-' . $to->format('Y'),
            'is_currentYear' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => null, // or a valid user ID
            'updated_by' => null, // or a valid user ID
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_years');
    }
};

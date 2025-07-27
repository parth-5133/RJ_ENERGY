<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->collation('latin1_swedish_ci');
            $table->foreignId('country_id')->constrained('countries', 'id');
        });

        // Insert Indian states data
        DB::table('states')->insert([
            ['name' => 'Andaman and Nicobar Islands', 'country_id' => 1],
            ['name' => 'Andhra Pradesh', 'country_id' => 1],
            ['name' => 'Arunachal Pradesh', 'country_id' => 1],
            ['name' => 'Assam', 'country_id' => 1],
            ['name' => 'Bihar', 'country_id' => 1],
            ['name' => 'Chandigarh', 'country_id' => 1],
            ['name' => 'Chhattisgarh', 'country_id' => 1],
            ['name' => 'Dadra and Nagar Haveli', 'country_id' => 1],
            ['name' => 'Daman and Diu', 'country_id' => 1],
            ['name' => 'Delhi', 'country_id' => 1],
            ['name' => 'Goa', 'country_id' => 1],
            ['name' => 'Gujarat', 'country_id' => 1],
            ['name' => 'Haryana', 'country_id' => 1],
            ['name' => 'Himachal Pradesh', 'country_id' => 1],
            ['name' => 'Jammu and Kashmir', 'country_id' => 1],
            ['name' => 'Jharkhand', 'country_id' => 1],
            ['name' => 'Karnataka', 'country_id' => 1],
            ['name' => 'Kenmore', 'country_id' => 1],
            ['name' => 'Kerala', 'country_id' => 1],
            ['name' => 'Lakshadweep', 'country_id' => 1],
            ['name' => 'Madhya Pradesh', 'country_id' => 1],
            ['name' => 'Maharashtra', 'country_id' => 1],
            ['name' => 'Manipur', 'country_id' => 1],
            ['name' => 'Meghalaya', 'country_id' => 1],
            ['name' => 'Mizoram', 'country_id' => 1],
            ['name' => 'Nagaland', 'country_id' => 1],
            ['name' => 'Narora', 'country_id' => 1],
            ['name' => 'Natwar', 'country_id' => 1],
            ['name' => 'Odisha', 'country_id' => 1],
            ['name' => 'Paschim Medinipur', 'country_id' => 1],
            ['name' => 'Pondicherry', 'country_id' => 1],
            ['name' => 'Punjab', 'country_id' => 1],
            ['name' => 'Rajasthan', 'country_id' => 1],
            ['name' => 'Sikkim', 'country_id' => 1],
            ['name' => 'Tamil Nadu', 'country_id' => 1],
            ['name' => 'Telangana', 'country_id' => 1],
            ['name' => 'Tripura', 'country_id' => 1],
            ['name' => 'Uttar Pradesh', 'country_id' => 1],
            ['name' => 'Uttarakhand', 'country_id' => 1],
            ['name' => 'Vaishali', 'country_id' => 1],
            ['name' => 'West Bengal', 'country_id' => 1],
        ]);
    }


    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};

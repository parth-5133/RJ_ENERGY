<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('phone');
            $table->string('alternate_mobile_no')->nullable();
            $table->string('email');
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('logo_display_name')->nullable();
            $table->string('website')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->foreignId('country')->nullable();
            $table->foreignId('state')->nullable();
            $table->string('city');
            $table->string('pin_code');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });

        DB::table('companies')->insert([
            'legal_name' => 'Skysphere Infosoft',
            'phone' => '9033889372',
            'alternate_mobile_no' => '9033889372',
            'email' => 'info@skysphereinfosoft.com',
            'gst_number' => '',
            'pan_number' => '',
            'logo_url' => null,
            'logo_display_name' => null,
            'website' => 'https://skysphereinfosoft.com',
            'address_line_1' => '323, Maruti Plaza, Krishnanagar Road, Ahmedabad - 380038',
            'address_line_2' => null,
            'address_line_3' => null,
            'country' => null,
            'state' => null,
            'city' => 'Ahmedabad',
            'pin_code' => '380038',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => null,
            'updated_by' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

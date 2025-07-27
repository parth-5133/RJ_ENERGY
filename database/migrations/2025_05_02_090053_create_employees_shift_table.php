<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees_shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
            $table->string('shift_name');
            $table->time('from_time');
            $table->time('to_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });

        // Insert default shifts
        DB::table('employees_shift')->insert([
            [
                'company_id'  => env("APP_BASE_COMPANYID"),
                'shift_name'  => 'Genaral Shift',
                'from_time'   => '09:00:00',
                'to_time'     => '17:00:00',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => null,
                'created_by'  => null,
                'updated_by'  => null,
            ],
            [
                'company_id'  => env("APP_BASE_COMPANYID"),
                'shift_name'  => 'Night Shift',
                'from_time'   => '22:00:00',
                'to_time'     => '06:00:00',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => null,
                'created_by'  => null,
                'updated_by'  => null,
            ],
            [
                'company_id'  => env("APP_BASE_COMPANYID"),
                'shift_name'  => 'First Half',
                'from_time'   => '09:00:00',
                'to_time'     => '13:00:00',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => null,
                'created_by'  => null,
                'updated_by'  => null,
            ],
            [
                'company_id'  => env("APP_BASE_COMPANYID"),
                'shift_name'  => 'Second Half',
                'from_time'   => '13:00:00',
                'to_time'     => '17:00:00',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => null,
                'created_by'  => null,
                'updated_by'  => null,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('employees_shift');
    }
};

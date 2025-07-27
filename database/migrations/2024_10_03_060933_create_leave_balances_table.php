<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('leave_balances')) {
            Schema::create('leave_balances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->nullable()->constrained('companies', 'id');
                $table->foreignId('employee_id')->constrained('users', 'id');
                $table->foreignId('leave_type_id')->constrained('leave_types', 'id');
                $table->float('balance')->default(0);
                $table->float('carry_forwarded')->default(0);
                $table->integer('financialYear_id');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users', 'id');
                $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            });

            // $currentYear = date('Y');
            // $leaveTypes = DB::table('leave_types')->where('year', $currentYear)->get();
            // $employees = DB::table('users')->get();

            // foreach ($employees as $employee) {
            //     foreach ($leaveTypes as $leaveType) {
            //         DB::table('leave_balances')->insert([
            //             'employee_id' => $employee->id,
            //             'leave_type_id' => $leaveType->id,
            //             'year' => $currentYear,
            //             'balance' => $leaveType->max_days,
            //             'carry_forwarded' => 0,
            //             'created_by' => 1,
            //             'updated_by' => null,
            //             'created_at' => now(),
            //             'updated_at' => null,
            //         ]);
            //     }
            // }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};

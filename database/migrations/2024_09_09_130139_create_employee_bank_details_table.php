<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('swift_code');
            $table->decimal('base_salary', 10, 2)->nullable();
            $table->boolean('bonus_eligibility')->default(false);
            $table->string('pay_grade')->nullable();
            $table->string('currency')->nullable();
            $table->unsignedBigInteger('payment_mode')->nullable()->constrained('payment_modes', 'id');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_financials');
    }

};

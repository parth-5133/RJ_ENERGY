<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 50);
            $table->string('branch_name', 50);
            $table->string('branch_manager_phone', 15);
            $table->string('loan_manager_phone', 15);
            $table->string('ifsc_code', 11);
            $table->string('address', 255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banks');
    }
}

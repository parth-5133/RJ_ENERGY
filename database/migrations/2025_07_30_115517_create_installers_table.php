<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallersTable extends Migration
{
    public function up()
    {
        Schema::create('installers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('phone', 15);
            $table->string('email');
            $table->text('address')->nullable();
            $table->softDeletes();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('installers');
    }
}

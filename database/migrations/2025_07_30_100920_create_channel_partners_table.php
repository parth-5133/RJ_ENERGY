<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelPartnersTable extends Migration
{
    public function up()
    {
        Schema::create('channel_partners', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('logo_url')->nullable(); // File path or URL
            $table->decimal('commission', 5, 2)->nullable();
            $table->string('phone', 15);
            $table->string('email')->nullable();
            $table->string('gst_number', 15)->nullable();
            $table->string('pan_number', 10)->nullable();
            $table->string('city');
            $table->string('pin_code');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_partners');
    }
}

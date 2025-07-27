<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_primaryid')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('document_type')->nullable();
            $table->string('relative_path');
            $table->string('file_id');
            $table->string('extension');
            $table->string('file_display_name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('app_documents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('placeholder_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placeholder_id');
            $table->foreignId('template_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placeholder_template');
    }
};

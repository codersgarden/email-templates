<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('placeholders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'username'
            $table->string('description')->nullable(); // Description of the placeholder
            $table->string('data_type')->nullable(); // e.g., 'string', 'date', 'integer'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placeholders');
    }
};
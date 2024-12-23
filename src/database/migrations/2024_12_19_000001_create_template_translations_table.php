<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id');
            $table->string('locale')->index();
            $table->string('subject');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_translations');
    }
}

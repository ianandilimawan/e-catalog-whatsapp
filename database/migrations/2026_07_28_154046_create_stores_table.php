<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->string('name');
            $table->string('slug');
            $table->string('wa_number');
            $table->string('theme_color')->nullable()->default('light');
            $table->text('welcome_message')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->boolean('button_rounded')->default(false);
            $table->boolean('dark_mode')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stores');
    }
};

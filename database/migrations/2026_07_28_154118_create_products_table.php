<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('store_id');
            $table->foreignId('category_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->integer('price');
            $table->string('image');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

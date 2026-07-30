<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('price');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->unsignedBigInteger('wa_checkout_clicks')->default(0)->after('dark_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('wa_checkout_clicks');
        });
    }
};

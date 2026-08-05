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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('google_analytics_id', 30)->nullable()->after('dark_mode');
            $table->string('meta_pixel_id', 30)->nullable()->after('google_analytics_id');
            $table->string('google_search_console_code', 100)->nullable()->after('meta_pixel_id');
            $table->string('seo_title', 120)->nullable()->after('google_search_console_code');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('cta_button_text', 50)->nullable()->after('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'google_analytics_id',
                'meta_pixel_id',
                'google_search_console_code',
                'seo_title',
                'seo_description',
                'cta_button_text',
            ]);
        });
    }
};

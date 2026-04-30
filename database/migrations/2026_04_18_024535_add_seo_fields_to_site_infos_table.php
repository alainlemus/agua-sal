<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->string('favicon')->nullable()->after('site_logo');
            $table->string('seo_title')->nullable()->after('favicon');
            $table->string('seo_description')->nullable()->after('seo_title');
            $table->string('seo_keywords')->nullable()->after('seo_description');
            $table->string('og_image')->nullable()->after('seo_keywords');
            $table->string('og_type')->default('website')->after('og_image');
            $table->string('twitter_card')->default('summary_large_image')->after('og_type');
            $table->string('twitter_site')->nullable()->after('twitter_card');
        });
    }

    public function down(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn([
                'favicon', 'seo_title', 'seo_description', 'seo_keywords',
                'og_image', 'og_type', 'twitter_card', 'twitter_site',
            ]);
        });
    }
};

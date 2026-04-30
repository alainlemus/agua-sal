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
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'modal_show_carousel')) {
                $table->boolean('modal_show_carousel')->default(false)->after('modal_button_url');
            }
            if (!Schema::hasColumn('pages', 'modal_carousel_items')) {
                $table->json('modal_carousel_items')->nullable()->after('modal_show_carousel');
            }
            if (!Schema::hasColumn('pages', 'modal_show_video')) {
                $table->boolean('modal_show_video')->default(false)->after('modal_carousel_items');
            }
            if (!Schema::hasColumn('pages', 'modal_video_url')) {
                $table->string('modal_video_url', 500)->nullable()->after('modal_show_video');
            }
            if (!Schema::hasColumn('pages', 'modal_video_autoplay')) {
                $table->boolean('modal_video_autoplay')->default(false)->after('modal_video_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'modal_show_carousel',
                'modal_carousel_items',
                'modal_show_video',
                'modal_video_url',
                'modal_video_autoplay',
            ]);
        });
    }
};

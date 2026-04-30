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
        Schema::table('site_infos', function (Blueprint $table) {
            $table->string('background_music')->nullable()->after('about_image');
            $table->boolean('auto_play_music')->default(false)->after('background_music');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn(['background_music', 'auto_play_music']);
        });
    }
};

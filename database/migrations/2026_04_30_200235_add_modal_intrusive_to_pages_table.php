<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'modal_intrusive')) {
                $table->boolean('modal_intrusive')->default(false)->after('modal_video_autoplay');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'modal_intrusive')) {
                $table->dropColumn('modal_intrusive');
            }
        });
    }
};
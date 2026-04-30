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
        Schema::table('menus', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
        });

        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn(['price_range', 'menu_subtitle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });

        Schema::table('site_infos', function (Blueprint $table) {
            $table->string('price_range')->nullable();
            $table->string('menu_subtitle')->nullable();
        });
    }
};

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
            $table->string('tagline')->nullable()->after('site_name');
            $table->string('serves_cuisine')->nullable()->after('tagline');
            $table->string('price_range')->nullable()->after('serves_cuisine');
            $table->string('menu_subtitle')->nullable()->after('price_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'serves_cuisine', 'price_range', 'menu_subtitle']);
        });
    }
};

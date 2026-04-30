<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('country', 100)->nullable()->after('user_agent');
            $table->string('country_code', 5)->nullable()->after('country');
            $table->string('city', 100)->nullable()->after('country_code');
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn(['country', 'country_code', 'city']);
        });
    }
};

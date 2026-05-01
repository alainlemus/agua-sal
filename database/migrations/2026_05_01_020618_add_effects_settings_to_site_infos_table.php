<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->boolean('enable_bubbles')->default(true);
            $table->boolean('enable_salt_effect')->default(true);
            $table->boolean('enable_waves')->default(true);
            $table->string('bubbles_density')->default('medium');
            $table->string('salt_density')->default('normal');
        });
    }

    public function down(): void
    {
        Schema::table('site_infos', function (Blueprint $table) {
            $table->dropColumn(['enable_bubbles', 'enable_salt_effect', 'enable_waves', 'bubbles_density', 'salt_density']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_nav')->default(false)->after('is_published');
            $table->string('nav_label')->nullable()->after('show_in_nav');
            $table->string('nav_icon')->nullable()->after('nav_label');  // emoji o texto
            $table->unsignedSmallInteger('nav_order')->default(0)->after('nav_icon');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['show_in_nav', 'nav_label', 'nav_icon', 'nav_order']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('modal_enabled')->default(false)->after('builder_content');
            $table->string('modal_title')->nullable()->after('modal_enabled');
            $table->text('modal_body')->nullable()->after('modal_title');
            $table->string('modal_button_label')->nullable()->after('modal_body');
            $table->string('modal_button_url')->nullable()->after('modal_button_label');
            $table->string('modal_delay')->default('0')->after('modal_button_url'); // segundos
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'modal_enabled',
                'modal_title',
                'modal_body',
                'modal_button_label',
                'modal_button_url',
                'modal_delay',
            ]);
        });
    }
};

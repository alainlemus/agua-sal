<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // ej. "Menú Ahumado"
            $table->string('slug')->unique();            // ej. "ahumado"
            $table->text('description')->nullable();
            $table->string('schedule')->nullable();      // ej. "Lun–Sáb 1pm–9pm"
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

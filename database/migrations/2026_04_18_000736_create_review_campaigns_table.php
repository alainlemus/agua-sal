<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Nombre interno ej. "Campaña Verano 2026"
            $table->string('slug')->unique();                // Token único en la URL del QR
            $table->string('gift_title');                    // ej. "¡Bebida gratis!"
            $table->text('gift_description')->nullable();    // Descripción del regalo
            $table->string('gift_code_prefix')->default('DON'); // Prefijo del código ej. DON-XXXX
            $table->boolean('is_active')->default(true);
            $table->integer('max_uses')->nullable();         // null = ilimitado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_campaigns');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            // Tipo de vista: home, menu, pagina, resena, contacto
            $table->string('type', 50)->index();
            // Slug o identificador legible (ej: "ahumados", "categoria:cortes", "resena:5")
            $table->string('slug', 150)->nullable()->index();
            // Referencia legible para mostrar en el panel
            $table->string('label', 200)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};

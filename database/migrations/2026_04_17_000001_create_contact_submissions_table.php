<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('form_page_slug')->nullable(); // de qué página vino
            $table->string('form_title')->nullable();     // título del bloque
            $table->json('fields_data');                  // respuestas: [{label, value}]
            $table->string('sender_name')->nullable();    // campo nombre si existe
            $table->string('sender_email')->nullable();   // campo email si existe
            $table->boolean('is_attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->string('attended_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};

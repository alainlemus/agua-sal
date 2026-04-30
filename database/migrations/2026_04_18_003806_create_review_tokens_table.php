<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();   // Token único en la URL
            $table->string('created_by')->nullable(); // Nombre del mesero que lo generó
            $table->timestamp('expires_at');           // Expiración configurable
            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_tokens');
    }
};

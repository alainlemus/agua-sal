<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->tinyInteger('rating');                   // 1-5 estrellas
            $table->text('comment')->nullable();
            $table->string('gift_code')->unique();           // Código único generado, ej. DON-A3K9
            $table->boolean('gift_redeemed')->default(false);
            $table->timestamp('gift_redeemed_at')->nullable();
            $table->string('gift_redeemed_by')->nullable();  // Nombre del empleado que lo cobró
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_submissions');
    }
};

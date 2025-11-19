<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('primary_color')->default('#2f80ed');
            $table->string('secondary_color')->default('#ffffff');
            $table->string('welcome_message')->default('Olá! Como posso ajudar?');
            $table->string('online_message')->default('Estamos online');
            $table->string('offline_message')->default('Estamos offline, deixe sua mensagem');
            $table->string('avatar_url')->nullable();
            $table->json('open_hours')->nullable(); // Ex: {"mon_fri":"09:00-18:00"}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_settings');
    }
};
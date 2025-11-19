<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('visitor_id'); // gerado pelo widget e guardado em localStorage
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->unique(['client_id', 'visitor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
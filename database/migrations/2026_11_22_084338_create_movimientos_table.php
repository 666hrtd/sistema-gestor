<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained('partidas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('posicion');
            $table->string('simbolo', 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
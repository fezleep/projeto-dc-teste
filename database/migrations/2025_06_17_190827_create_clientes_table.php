<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // Cria uma coluna 'id' auto-incrementável (UNSIGNED BIGINT)
            $table->string('nome');
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->unique()->nullable(); // Adicionando email como único e opcional
            $table->timestamps(); // Adiciona 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
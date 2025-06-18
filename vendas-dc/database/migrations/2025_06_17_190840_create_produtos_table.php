<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Esta linha 'dropIfExists' na função up() não é necessária quando você usa 'migrate:fresh'
        // porque 'migrate:fresh' já apaga todas as tabelas antes de recriá-las.
        // É mais comum ter 'dropIfExists' apenas na função 'down()'.
        // Se desejar, pode remover a linha abaixo.
        // Schema::dropIfExists('produtos');

        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('preco', 10, 2);
            $table->text('descricao')->nullable();
            $table->integer('estoque'); // <-- ESTA LINHA AGORA ESTÁ ATIVA E CORRETA!
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
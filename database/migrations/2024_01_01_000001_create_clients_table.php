<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // ID da tabela
            $table->string('name'); // Exemplo de coluna
            $table->string('email')->unique(); // Outro exemplo
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('clients');
    }
};
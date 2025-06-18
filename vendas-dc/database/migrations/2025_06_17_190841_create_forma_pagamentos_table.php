<?php
// database/migrations/2025_06_17_190841_create_forma_pagamentos_table.php (ou o nome do seu arquivo de migração)
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
        Schema::create('forma_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');         // Adicione esta linha
            $table->integer('quantidade_parcelas'); // Adicione esta linha
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forma_pagamentos');
    }
};
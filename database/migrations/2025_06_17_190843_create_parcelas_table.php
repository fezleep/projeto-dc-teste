<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('vendas')->onDelete('cascade');
            $table->integer('numero');
            $table->decimal('valor', 10, 2);
            $table->date('vencimento'); // Data de vencimento
            $table->string('status')->default('pendente'); // Campo status com valor padrão
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
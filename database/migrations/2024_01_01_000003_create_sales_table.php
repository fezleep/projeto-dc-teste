<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // A chave estrangeira para client_id está correta, referenciando a tabela 'clients' por padrão
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();

            // CORREÇÃO: Especificamos explicitamente que user_id referencia a tabela 'clients'
            $table->foreignId('user_id')->constrained('clients')->onDelete('cascade');

            $table->string('payment_method');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
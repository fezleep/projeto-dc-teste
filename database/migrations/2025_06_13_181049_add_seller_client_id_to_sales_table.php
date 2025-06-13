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
        Schema::table('sales', function (Blueprint $table) {
            // Adiciona a coluna seller_client_id como chave estrangeira para a tabela clients
            // Assumimos que 'clients' é a tabela onde os vendedores (usuários) estão
            $table->foreignId('seller_client_id')->nullable()->constrained('clients')->onDelete('set null');
            // ->nullable() permite que a coluna seja nula se o vendedor for excluído (onDelete('set null'))
            // ->constrained('clients') cria a chave estrangeira para a tabela clients
            // ->onDelete('set null') define que se um cliente (vendedor) for excluído, essa coluna se torna NULL
            // Se você quiser que a venda seja excluída junto com o vendedor, mude para ->onDelete('cascade')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Primeiro, remova a chave estrangeira
            $table->dropConstrainedForeignId('seller_client_id');
            // Depois, remova a coluna
            $table->dropColumn('seller_client_id');
        });
    }
};
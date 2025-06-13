<?php
// Exemplo: 2025_06_13_xxxxxx_remove_stock_from_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Verifica se a coluna 'stock' existe antes de tentar removê-la
            // Isso evita o erro 'no such column' se a migração for rodada novamente
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Se precisar reverter, defina o tipo de volta (ex: $table->integer('stock')->default(0);)
            // Adicione if ( ! Schema::hasColumn('products', 'stock')) para evitar erro de 'column already exists'
            if (! Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0);
            }
        });
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormaPagamento;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormaPagamento::firstOrCreate(['descricao' => 'Cartão de Crédito'], [
            'quantidade_parcelas' => 3,
        ]);

        FormaPagamento::firstOrCreate(['descricao' => 'Boleto Bancário'], [
            'quantidade_parcelas' => 1,
        ]);

        FormaPagamento::firstOrCreate(['descricao' => 'Pix'], [
            'quantidade_parcelas' => 1,
        ]);

        FormaPagamento::firstOrCreate(['descricao' => 'Cartão de Débito'], [
            'quantidade_parcelas' => 1,
        ]);

        FormaPagamento::firstOrCreate(['descricao' => 'Cartão de Crédito (12x)'], [
            'quantidade_parcelas' => 12,
        ]);
    }
}
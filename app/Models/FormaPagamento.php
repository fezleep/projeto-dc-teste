<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    use HasFactory;

    protected $table = 'forma_pagamentos'; // Garante que o nome da tabela está correto
    protected $fillable = ['descricao', 'quantidade_parcelas'];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
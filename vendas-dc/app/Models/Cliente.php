<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'endereco', 'telefone', 'email'];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        // Adicione os campos da sua tabela clients aqui (ex: 'name', 'email', 'password' se for o usuário)
        // Exemplo: 'name', 'email', 'phone', 'address'
    ];

    // Relação: Um cliente pode ter muitas vendas (como cliente)
    public function salesAsClient()
    {
        return $this->hasMany(Sale::class, 'client_id');
    }

    // Relação: Um cliente pode ter muitas vendas (como vendedor/user)
    public function salesAsUser()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }
}
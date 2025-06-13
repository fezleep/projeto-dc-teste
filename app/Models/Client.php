<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- Adicione esta linha
use Illuminate\Notifications\Notifiable; // <-- Adicione esta linha (opcional, para notificações)

// Altere 'extends Model' para 'extends Authenticatable'
class Client extends Authenticatable
{
    use HasFactory, Notifiable; // <-- Adicione Notifiable aqui (opcional)

    // Seus campos fillable já definidos, incluindo 'name' e 'email'
    protected $fillable = [
        'name',
        'email',
        'password', // <-- Adicione 'password' aqui também, pois agora é uma coluna
        // Se tiver 'phone', 'address', etc., mantenha-os aqui.
    ];

    // Estes são importantes para a autenticação (ocultam o password ao serializar o Model)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Seus relacionamentos
    public function salesAsClient()
    {
        return $this->hasMany(Sale::class, 'client_id');
    }

    public function salesAsUser()
    {
        return $this->hasMany(Sale::class, 'user_id');
    }
}
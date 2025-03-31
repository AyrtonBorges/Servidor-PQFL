<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['usuario', 'senha'];

    protected $hidden = ['senha'];

    // Substituir "password" por "senha" para autenticação
    public function getAuthPassword()
    {
        return $this->senha;
    }
}

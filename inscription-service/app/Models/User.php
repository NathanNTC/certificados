<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    // Relacionamento com inscrições
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class); // Um usuário pode ter muitas inscrições
    }
}
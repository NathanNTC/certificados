<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    // Atributos que podem ser preenchidos em massa
    protected $fillable = [
        'event_id',   // Adicione o event_id aqui
        'codigo',
        'email',
    ];

    // Definindo a tabela associada
    protected $table = 'checkins';

    // Função para definir o relacionamento com o modelo Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

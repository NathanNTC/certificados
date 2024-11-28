<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Atributos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'nome',        // Nome do evento
        'codigo',      // Código do evento (ex: HUAL-123)
        'descricao',   // Descrição do evento
        'data_inicio', // Data de início do evento
        'data_fim',    // Data de fim do evento
        'local',       // Local do evento
    ];

    // Definindo a tabela associada
    protected $table = 'events';

    // Caso as datas sejam manipuladas automaticamente pelo Laravel
    protected $dates = ['data_inicio', 'data_fim'];

    // Função para definir o relacionamento com o modelo Checkin
    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    // Outras funções ou escopos podem ser adicionados conforme necessário
}

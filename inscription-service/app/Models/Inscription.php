<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    // Campos preenchíveis
    protected $fillable = ['user_email', 'event_id'];

    /**
     * Relação com o usuário baseado no e-mail.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'user_email');
    }

    /**
     * Relação com o evento.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

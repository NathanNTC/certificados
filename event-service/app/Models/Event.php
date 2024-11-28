<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    use HasFactory;

    
    protected $fillable = ['nome', 'data', 'local', 'codigo'];

    
    protected $table = 'events';

        // Modelo Event
    

}



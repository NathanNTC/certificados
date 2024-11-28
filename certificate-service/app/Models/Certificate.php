<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    // Remova o 'event_date' aqui
    protected $fillable = ['event_name', 'user_name', 'certificate_id', 'is_valid'];
}

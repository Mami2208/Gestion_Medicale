<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $table = 'alertes';
    
    protected $fillable = [
        'titre',
        'description'
    ];

    // Vous pouvez ajouter ici des relations ou des méthodes supplémentaires si nécessaire.
} 
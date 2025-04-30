<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    protected $fillable = [
        'matricule',
        'specialite',
        'utilisateur_id',
    ];
}

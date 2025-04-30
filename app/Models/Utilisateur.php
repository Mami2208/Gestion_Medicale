<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    protected $fillable = [
        'nom', 
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'specialite',
        'telephone',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function medecin()
    {
        return $this->hasOne(\App\Models\Medecin::class, 'utilisateur_id');
    }
}

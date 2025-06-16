<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secretaire_medical extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'matricule'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

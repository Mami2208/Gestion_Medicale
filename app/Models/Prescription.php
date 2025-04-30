<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'duree',
        'medicament',
        'posologie',
        'frequence',
        'medecin_id',
    ];
}

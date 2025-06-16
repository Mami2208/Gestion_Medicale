<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rendez_Vous extends Model
{
    protected $table = 'rendez_vous';

    protected $fillable = [
        'medecin_id',
        'patient_id',
        'date_rendez_vous',
        'heure_debut',
        'heure_fin',
        'motif',
        'notes',
        'statut'
    ];

    protected $casts = [
        'date_rendez_vous' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime'
    ];

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

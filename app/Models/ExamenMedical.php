<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenMedical extends Model
{
    protected $table = 'examens';
    
    protected $fillable = [
        'dossiers_medicaux_id',
        'date',
        'type_examen',
        'resultat',
        'medecin_id',
        'notes',
        'statut',
        'description',
        'conclusion',
        'patient_id'
    ];

    protected $dates = ['date'];
    
    /**
     * Relation avec le patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relation avec le dossier médical
     */
    public function dossierMedical()
    {
        return $this->belongsTo(Dossier::class, 'dossiers_medicaux_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }
}

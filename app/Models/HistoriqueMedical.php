<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueMedical extends Model
{
    protected $table = 'historiques_medicaux';
    
    protected $fillable = [
        'dossier_medical_id',
        'date',
        'description',
        'type',
        'medecin_id',
        'notes'
    ];

    protected $dates = ['date'];

    /**
     * Relation avec le dossier médical
     */
    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }
}

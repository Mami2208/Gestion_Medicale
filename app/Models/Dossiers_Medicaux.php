<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Consultation;
use App\Models\HistoriqueMedical;
use App\Models\ExamenMedical;
use App\Models\Prescription;

class Dossiers_Medicaux extends Model
{
    use HasFactory;

    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'numero_dossier',
        'date_creation',
        'observations',
        'antecedents',
        'antecedents_medicaux',
        'allergies',
        'groupe_sanguin',
        'taille',
        'poids',
        'statut',
        'motif_consultation',
        'traitements_en_cours'
    ];

    protected $dates = [
        'date_creation',
        'created_at',
        'updated_at'
    ];

    /**
     * Relation avec le patient
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    
    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    /**
     * Relation avec les consultations
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'dossier_medical_id');
    }

    /**
     * Relation avec les historiques médicaux
     */
    public function historiques(): HasMany
    {
        return $this->hasMany(HistoriqueMedical::class, 'dossier_medical_id');
    }

    /**
     * Relation avec les examens médicaux
     */
    public function examens(): HasMany
    {
        return $this->hasMany(ExamenMedical::class, 'dossier_medical_id');
    }

    /**
     * Relation avec les prescriptions
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'dossier_medical_id');
    }
}

<?php

namespace App\Models;

use App\Models\DicomStudy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date_consultation',
        'motif',
        'symptomes',
        'diagnostic',
        'traitement',
        'notes',
        'statut',
        'poids',
        'taille',
        'temperature',
        'tension_arterielle',
        'pouls',
        'frequence_respiratoire',
        'saturation_o2',
        'antecedents_medicaux',
        'allergies',
        'traitement_en_cours',
        'examen_clinique',
        'examen_complementaire',
        'orientation',
        'compte_rendu',
        'date_prochaine_visite',
        'motif_prochaine_visite',
        'type_consultation',
        'duree_consultation',
        'is_urgent',
        'is_teleconsultation',
        'lien_teleconsultation',
        'est_terminee',
        'date_fin_consultation',
    ];

    protected $casts = [
        'date_consultation' => 'datetime',
        'montant' => 'decimal:2',
        'paye' => 'boolean'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class);
    }
    
    /**
     * Relation avec les téléversements DICOM
     */
    public function dicomUploads()
    {
        return $this->hasMany(\App\Models\DicomUpload::class);
    }

    /**
     * Obtenir les études DICOM associées à cette consultation.
     */
    public function dicomStudies(): HasMany
    {
        return $this->hasMany(DicomStudy::class, 'consultation_id');
    }
    
    /**
     * Obtenir les prescriptions associées à cette consultation.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'consultation_id');
    }
    
    /**
     * Alias pour la relation prescriptions (compatibilité avec le code existant)
     */
    public function ordonnances()
    {
        return $this->prescriptions();
    }
    
    /**
     * Obtenir les examens associés à cette consultation.
     */
    public function examens()
    {
        return $this->hasMany(Examen::class, 'consultation_id');
    }
}

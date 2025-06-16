<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'utilisateur_id',
        'numeroPatient',
        'adresse',
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'telephone',
        'email',
        'groupe_sanguin'
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_naissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur associé.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    
    /**
     * Relation avec les études DICOM du patient.
     */
    public function dicomStudies(): HasMany
    {
        return $this->hasMany(DicomStudy::class, 'patient_id');
    }

    /**
     * Relation avec les dossiers médicaux.
     */
    public function dossiers(): HasMany
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Relation avec les rendez-vous.
     */
    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }

    /**
     * Relation avec les consultations.
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Relation avec le dossier médical.
     */
    public function dossierMedical(): HasOne
    {
        return $this->hasOne(DossierMedical::class);
    }

    /**
     * Alias pour la relation dossierMedical.
     * Certaines parties du code utilisent dossier_medical (avec underscore)
     */
    public function dossier_medical(): HasOne
    {
        return $this->dossierMedical();
    }

    public function historiqueMedicals(): HasMany
    {
        return $this->hasMany(HistoriqueMedical::class);
    }

    public function traitements(): HasMany
    {
        return $this->hasMany(Traitement::class);
    }

    /**
     * Relation avec les observations cliniques.
     */
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }
    
    /**
     * Relation avec l'infirmier assigné au patient.
     */
    public function infirmier(): BelongsTo
    {
        return $this->belongsTo(Infirmier::class, 'infirmier_id');
    }

    /**
     * Crée une notification lorsqu'un patient est assigné à un infirmier
     * 
     * @param int $infirmierId ID de l'infirmier
     * @param int|null $oldInfirmierId Ancien ID de l'infirmier (si modification)
     * @return void
     */
    public function notifyAssignmentToNurse($infirmierId, $oldInfirmierId = null)
    {
        // Ne pas notifier si l'infirmier n'a pas changé
        if ($oldInfirmierId === $infirmierId) {
            return;
        }
        
        try {
            // Récupérer l'infirmier et son utilisateur associé
            $newInfirmier = \App\Models\Infirmier::find($infirmierId);
            if ($newInfirmier && $newInfirmier->utilisateur) {
                // Créer une notification pour le nouvel infirmier en utilisant le système de notifications de Laravel
                $newInfirmier->utilisateur->notify(new \App\Notifications\PatientAssigned([
                    'title' => 'Nouveau patient assigné',
                    'message' => 'Un nouveau patient vous a été assigné: ' . $this->utilisateur->prenom . ' ' . $this->utilisateur->nom,
                    'type' => 'ASSIGNMENT',
                    'patient_id' => $this->id
                ]));
            }
            
            // Si ce patient était précédemment assigné à un autre infirmier, le notifier également
            if ($oldInfirmierId) {
                $oldInfirmier = \App\Models\Infirmier::find($oldInfirmierId);
                if ($oldInfirmier && $oldInfirmier->utilisateur) {
                    $oldInfirmier->utilisateur->notify(new \App\Notifications\PatientReassigned([
                        'title' => 'Patient réassigné',
                        'message' => 'Le patient ' . $this->utilisateur->prenom . ' ' . $this->utilisateur->nom . ' a été réassigné à un autre infirmier',
                        'type' => 'REASSIGNMENT',
                        'patient_id' => $this->id
                    ]));
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur, journaliser sans interrompre le flux de l'application
            \Log::error('Erreur lors de la création de notification: ' . $e->getMessage());
        }
    }

    /**
     * Relation avec les dossiers médicaux.
     * Cette méthode est un alias pour dossiers() mais spécifiquement pour les dossiers médicaux.
     */
    public function dossiers_medicaux(): HasMany
    {
        return $this->hasMany(DossierMedical::class);
    }

    /**
     * Obtenir le nom complet du patient.
     */
    public function getNomCompletAttribute()
    {
        return $this->utilisateur ? $this->utilisateur->nom . ' ' . $this->utilisateur->prenom : null;
    }

    /**
     * Obtenir l'âge du patient.
     */
    public function getAgeAttribute()
    {
        return $this->utilisateur ? $this->utilisateur->age : null;
    }
}

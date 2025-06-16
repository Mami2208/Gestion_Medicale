<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Patient;
use App\Models\Infirmier;
use App\Models\Medecin;

class Observation extends Model
{
    use SoftDeletes;

    // Types d'observation
    public const TYPE_OBSERVATION = 'observation';
    public const TYPE_SYMPTOME = 'symptome';
    public const TYPE_EXAMEN = 'examen';
    public const TYPE_SUIVI = 'suivi';
    public const TYPE_AUTRE = 'autre';

    // Statuts
    public const STATUT_ACTIF = 'actif';
    public const STATUT_ARCHIVE = 'archive';
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINE = 'termine';
    public const STATUT_ANNULE = 'annule';

    /**
     * Les types d'observation disponibles.
     *
     * @var array
     */
    public const TYPES = [
        self::TYPE_OBSERVATION => 'Observation générale',
        self::TYPE_SYMPTOME => 'Symptôme',
        self::TYPE_EXAMEN => 'Examen clinique',
        self::TYPE_SUIVI => 'Suivi de traitement',
        self::TYPE_AUTRE => 'Autre',
    ];

    /**
     * Les statuts possibles.
     *
     * @var array
     */
    public const STATUTS = [
        self::STATUT_ACTIF => 'Actif',
        self::STATUT_ARCHIVE => 'Archivé',
        self::STATUT_EN_COURS => 'En cours',
        self::STATUT_TERMINE => 'Terminé',
        self::STATUT_ANNULE => 'Annulé',
    ];

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'infirmier_id',
        'medecin_id',
        'contenu',
        'type_observation',
        'statut',
        'est_urgent',
        'date_observation',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_observation' => 'datetime',
        'est_urgent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];



    /**
     * Relation avec le modèle Patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relation avec le modèle Infirmier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function infirmier(): BelongsTo
    {
        return $this->belongsTo(Infirmier::class, 'infirmier_id', 'utilisateur_id');
    }
    
    /**
     * Relation avec le modèle Medecin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class, 'medecin_id', 'utilisateur_id');
    }

    /**
     * Récupère le libellé du type d'observation.
     *
     * @return string
     */
    public function getTypeLibelleAttribute(): string
    {
        if (empty($this->type_observation)) {
            return 'Non spécifié';
        }
        return self::TYPES[$this->type_observation] ?? $this->type_observation;
    }

    /**
     * Récupère le libellé du statut.
     *
     * @return string
     */
    public function getStatutLibelleAttribute(): string
    {
        if (empty($this->statut)) {
            return 'Non spécifié';
        }
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /**
     * Scope pour les observations importantes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImportant($query)
    {
        return $query->where('est_important', true);
    }

    /**
     * Scope pour les observations actives.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'ACTIF');
    }
}

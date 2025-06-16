<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Traitement extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'type_traitement',
        'description',
        'date_debut',
        'date_fin',
        'statut',
        'observations',
        'dossier_medical_id'
    ];
    
    /**
     * Le nom de la colonne utilisée pour le statut
     *
     * @var string
     */
    protected $statusColumn = 'statut';

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'est_important' => 'boolean'
    ];
    
    /**
     * Obtenir le nom de la colonne de statut
     *
     * @return string
     */
    public function getStatusColumn()
    {
        return $this->statusColumn;
    }
    
    /**
     * Scope pour récupérer uniquement les traitements actifs
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActifs($query)
    {
        return $query->whereNotIn($this->getStatusColumn(), ['TERMINE', 'ANNULE']);
    }
    
    /**
     * Les attributs qui doivent être ajoutés à la représentation tableau/JSON du modèle.
     *
     * @var array
     */
    protected $appends = ['duree_restante', 'statut_libelle', 'type_libelle'];
    
    /**
     * Les statuts possibles pour un traitement
     *
     * @var array
     */
    public const STATUTS = [
        'EN_ATTENTE' => 'En attente',
        'EN_COURS' => 'En cours',
        'TERMINE' => 'Terminé',
        'ANNULE' => 'Annulé',
        'PAUSE' => 'En pause'
    ];
    
    /**
     * Les types de traitement possibles
     *
     * @var array
     */
    public const TYPES = [
        'MEDICAMENT' => 'Médicament',
        'KINESITHERAPIE' => 'Kinésithérapie',
        'PANSEMENT' => 'Pansement',
        'SOINS_INFIRMIER' => 'Soins infirmiers',
        'AUTRE' => 'Autre'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function historiqueMedical(): BelongsTo
    {
        return $this->belongsTo(HistoriqueMedical::class);
    }

    /**
     * Obtenir les prescriptions associées à ce traitement
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
    
    /**
     * Obtenir les médicaments associés à ce traitement
     */
    public function medicaments(): BelongsToMany
    {
        return $this->belongsToMany(Medicament::class)
            ->withPivot(['posologie', 'frequence', 'duree_jours', 'instructions'])
            ->withTimestamps();
    }
    
    /**
     * Calcule et retourne la durée restante du traitement
     * 
     * @return string
     */
    public function getDureeRestanteAttribute()
    {
        if (!$this->date_fin) {
            return 'Durée indéterminée';
        }
        
        $now = now();
        $end = $this->date_fin;
        
        if ($now > $end) {
            return 'Terminé';
        }
        
        $diff = $now->diff($end);
        
        if ($diff->days === 0) {
            return 'Aujourd\'hui';
        } elseif ($diff->days === 1) {
            return '1 jour';
        } elseif ($diff->days < 7) {
            return $diff->days . ' jours';
        } elseif ($diff->days < 30) {
            $weeks = floor($diff->days / 7);
            return $weeks . ' semaine' . ($weeks > 1 ? 's' : '');
        } else {
            $months = $diff->y * 12 + $diff->m;
            return $months . ' mois';
        }
    }
    
    /**
     * Retourne le libellé du statut du traitement
     *
     * @return string
     */
    public function getStatutLibelleAttribute()
    {
        return self::STATUTS[$this->statut] ?? 'Inconnu';
    }
    
    /**
     * Retourne le libellé du type de traitement
     *
     * @return string
     */
    public function getTypeLibelleAttribute()
    {
        return self::TYPES[$this->type_traitement] ?? 'Autre';
    }
    

    /**
     * Scope pour les traitements en attente
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnAttente($query)
    {
        return $query->where($this->getStatusColumn(), 'EN_ATTENTE');
    }
    
    /**
     * Scope pour les traitements en cours
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnCours($query)
    {
        return $query->where($this->getStatusColumn(), 'EN_COURS');
    }
    
    /**
     * Scope pour les traitements à venir (date de début dans le futur)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAVenir($query)
    {
        return $query->whereDate('date_debut', '>', now());
    }
    
    /**
     * Scope pour les traitements en retard (date de fin dépassée et non terminés)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnRetard($query)
    {
        return $query->whereDate('dateFin', '<', now())
                    ->whereNotIn($this->getStatusColumn(), ['TERMINE', 'ANNULE']);
    }
}

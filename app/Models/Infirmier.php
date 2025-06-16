<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Infirmier extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'matricule',
        'secteur'
    ];
    
    /**
     * Nombre maximum recommandé de patients par infirmier
     */
    const CHARGE_TRAVAIL_MAX = 10;

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    
    /**
     * Relation avec les patients assignés à cet infirmier
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'infirmier_id');
    }
    
    /**
     * Calcule le nombre de patients assignés à cet infirmier
     * 
     * @return int
     */
    public function getNombrePatientsAttribute(): int
    {
        return $this->patients()->count();
    }
    
    /**
     * Calcule le pourcentage de charge de travail de l'infirmier
     * basé sur le nombre maximum recommandé de patients
     * 
     * @return float
     */
    public function getPourcentageChargeAttribute(): float
    {
        return min(100, ($this->nombre_patients * 100) / self::CHARGE_TRAVAIL_MAX);
    }
    
    /**
     * Détermine si l'infirmier a une charge de travail élevée
     * 
     * @return bool
     */
    public function getChargeEleveeAttribute(): bool
    {
        return $this->pourcentage_charge >= 80;
    }
    
    /**
     * Retourne la catégorie de charge de travail de l'infirmier
     * 
     * @return string 'faible', 'moyenne', 'elevee'
     */
    public function getCategorieChargeAttribute(): string
    {
        $pourcentage = $this->pourcentage_charge;
        
        if ($pourcentage < 40) {
            return 'faible';
        } elseif ($pourcentage < 80) {
            return 'moyenne';
        } else {
            return 'elevee';
        }
    }
}

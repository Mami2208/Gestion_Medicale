<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medecin extends Model
{
    protected $fillable = [
        'matricule',
        'specialite',
        'utilisateur_id',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    /**
     * Relation avec les dossiers médicaux
     */
    public function dossiers(): HasMany
    {
        return $this->hasMany(Dossier::class, 'medecin_id');
    }

    /**
     * Relation avec les consultations
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class, 'medecin_id');
    }

    /**
     * Relation avec les rendez-vous
     */
    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'forme_pharmaceutique',
        'voie_administration',
        'dose',
        'unite_mesure',
        'code_cip',
        'sur_ordonnance',
        'est_actif'
    ];

    protected $casts = [
        'sur_ordonnance' => 'boolean',
        'est_actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'unite_mesure' => 'mg',
        'sur_ordonnance' => true,
        'est_actif' => true
    ];

    public function traitements(): BelongsToMany
    {
        return $this->belongsToMany(Traitement::class)
            ->withPivot(['posologie', 'frequence', 'duree_jours', 'instructions'])
            ->withTimestamps();
    }
} 
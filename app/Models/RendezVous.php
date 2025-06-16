<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';
    protected $fillable = [
        'medecin_id',
        'patient_id',
        'date_rendez_vous',
        'heure_debut',
        'heure_fin',
        'motif',
        'notes',
        'type_rendez_vous',
        'duree',
        'lieu',
        'statut',
        'raison_annulation',
        'date_confirmation',
        'confirme_par',
        'est_urgent',
        'instructions_preparation',
        'numero_salle'
    ];

    protected $casts = [
        'date_rendez_vous' => 'date',
        'est_urgent' => 'boolean',
        'date_confirmation' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rendezVous) {
            // Définir des valeurs par défaut si nécessaire
            $rendezVous->statut = $rendezVous->statut ?? 'PLANIFIE';
            $rendezVous->type_rendez_vous = $rendezVous->type_rendez_vous ?? 'CONSULTATION';
            $rendezVous->est_urgent = $rendezVous->est_urgent ?? false;
        });
    }



    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function scopeAvenir($query)
    {
        return $query->where('date_rendez_vous', '>', now());
    }

    public function scopePasses($query)
    {
        return $query->where('date_rendez_vous', '<=', now());
    }
}

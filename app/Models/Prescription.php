<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DossierMedical;
use App\Models\Medecin;

class Prescription extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'dossier_medical_id',
        'traitement_id',
        'medicament',
        'posologie',
        'frequence',
        'duree_jours',
        'instructions',
        'statut',
        'date_prescription',
        'medecin_id'
    ];
    
    protected $dates = ['date_prescription'];

    protected $casts = [
        'duree_jours' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Les statuts possibles pour une prescription
     *
     * @var array
     */
    public const STATUTS = [
        'EN_ATTENTE' => 'En attente',
        'EN_COURS' => 'En cours',
        'TERMINEE' => 'Terminée',
        'ANNULEE' => 'Annulée'
    ];

    /**
     * Relation avec le dossier médical
     */
    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class, 'dossier_medical_id');
    }
    
    /**
     * Obtenir le traitement associé à cette prescription
     */
    public function traitement(): BelongsTo
    {
        return $this->belongsTo(Traitement::class);
    }
    
    /**
     * Relation avec le médecin prescripteur
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? 'Inconnu';
    }
}

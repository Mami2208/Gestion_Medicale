<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_examen',
        'patient_id',
        'medecin_id',
        'type_examen',
        'date_examen',
        'description',
        'conclusion',
        'statut',
        'dossiers_medicaux_id'
    ];

    protected $casts = [
        'date_examen' => 'datetime'
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
        return $this->belongsTo(DossierMedical::class, 'dossiers_medicaux_id');
    }

    public static function genererNumeroExamen(): string
    {
        $annee = date('Y');
        $mois = date('m');
        $dernierExamen = Examen::whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->orderBy('created_at', 'desc')
            ->first();

        $numero = $dernierExamen ? $dernierExamen->numero_examen : null;
        $numero = $numero ? (int)substr($numero, -4) + 1 : 1;
        
        return sprintf('EX%02d%02d%04d', $annee, $mois, $numero);
    }
}

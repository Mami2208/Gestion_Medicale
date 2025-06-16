<?php

namespace App\Http\Controllers;
// Si tu utilises un modèle
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patient; // Assurez-vous d'importer le modèle Patient
class DossiersMedicauxController extends Controller
{
    use HasFactory;

    // Définir les attributs remplissables
    protected $fillable = [
        'patient_id',
        'diagnostic',
        'prescription',
    ];

    // Spécifier le nom de la table si elle diffère du nom par défaut
    protected $table = 'dossiers__medicauxes';

    // Relation avec le modèle Patient
   
}

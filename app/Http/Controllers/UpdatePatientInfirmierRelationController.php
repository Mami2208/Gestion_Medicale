<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Infirmier;
use App\Models\Patient;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdatePatientInfirmierRelationController extends Controller
{
    /**
     * Met à jour la relation entre patients et infirmiers
     */
    public function updateRelation()
    {
        $message = '';
        
        // 1. Vérifier si la colonne infirmier_id existe dans la table patients
        if (!Schema::hasColumn('patients', 'infirmier_id')) {
            try {
                // Ajouter la colonne infirmier_id à la table patients
                DB::statement('ALTER TABLE patients ADD COLUMN infirmier_id INT NULL');
                
                // Ajouter la contrainte de clé étrangère
                DB::statement('ALTER TABLE patients ADD CONSTRAINT fk_patients_infirmier FOREIGN KEY (infirmier_id) REFERENCES infirmiers(id) ON DELETE SET NULL');
                
                $message .= "Colonne infirmier_id ajoutée à la table patients. ";
            } catch (\Exception $e) {
                // En cas d'erreur, simplement ajouter la colonne sans contrainte
                try {
                    DB::statement('ALTER TABLE patients ADD COLUMN infirmier_id INT NULL');
                    $message .= "Colonne infirmier_id ajoutée sans contrainte de clé étrangère. ";
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Erreur lors de l\'ajout de la colonne: ' . $e->getMessage());
                }
            }
        } else {
            $message .= "La colonne infirmier_id existe déjà. ";
        }
        
        // 2. Assigner aléatoirement des patients aux infirmiers pour les tests
        $infirmiers = Infirmier::all();
        
        if ($infirmiers->count() > 0) {
            $patients = Patient::whereNull('infirmier_id')->get();
            
            foreach ($patients as $index => $patient) {
                // Assigner chaque patient à un infirmier aléatoire
                $infirmier = $infirmiers[$index % $infirmiers->count()];
                $patient->infirmier_id = $infirmier->id;
                $patient->save();
            }
            
            $message .= count($patients) . " patients ont été assignés aux infirmiers.";
        } else {
            $message .= "Aucun infirmier trouvé pour assigner des patients.";
        }
        
        // Enregistrer l'action dans les logs
        ActivityLogService::log(
            'update_relation',
            'system',
            'Mise à jour de la relation entre patients et infirmiers',
            null,
            ['message' => $message]
        );
        
        return redirect()->back()->with('success', $message);
    }
}

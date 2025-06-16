<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medecin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DebugPatientController extends Controller
{
    public function debugPatients()
    {
        // Vérifier si l'utilisateur est connecté et est un médecin
        if (!auth()->check()) {
            return response()->json(['error' => 'Utilisateur non connecté'], 403);
        }

        $user = auth()->user();
        $debug = [];
        $debug['utilisateur'] = [
            'id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'role' => $user->role
        ];

        // 1. Vérifier si la table des médecins existe et sa structure
        $debug['tables'] = Schema::getAllTables();
        $debug['medecins_columns'] = Schema::getColumnListing('medecins');
        $debug['patients_columns'] = Schema::getColumnListing('patients');

        // 2. Récupérer le médecin correspondant à l'utilisateur
        $medecin = Medecin::where('utilisateur_id', $user->id)->first();
        
        if (!$medecin) {
            $debug['medecin'] = "Aucun médecin trouvé pour l'utilisateur #" . $user->id;
        } else {
            $debug['medecin'] = [
                'id' => $medecin->id,
                'matricule' => $medecin->matricule,
                'specialite' => $medecin->specialite
            ];

            // 3. Rechercher les patients par différentes méthodes
            
            // Méthode 1: patients.medecin_id = medecins.id
            $patients1 = Patient::where('medecin_id', $medecin->id)->get();
            $debug['methode1'] = [
                'query' => "Patient::where('medecin_id', " . $medecin->id . ")->get()",
                'count' => $patients1->count(),
                'patients' => []
            ];
            
            foreach ($patients1 as $p) {
                $debug['methode1']['patients'][] = [
                    'id' => $p->id,
                    'nom' => $p->nom,
                    'prenom' => $p->prenom
                ];
            }
            
            // Méthode 2: Tous les patients (pour comparaison)
            $allPatients = Patient::limit(10)->get();
            $debug['all_patients'] = [
                'count' => Patient::count(),
                'sample' => []
            ];
            
            foreach ($allPatients as $p) {
                $debug['all_patients']['sample'][] = [
                    'id' => $p->id,
                    'nom' => $p->nom,
                    'prenom' => $p->prenom,
                    'medecin_id' => $p->medecin_id,
                    'utilisateur_id' => $p->utilisateur_id
                ];
            }
            
            // Méthode 3: Requête SQL directe pour vérifier si la colonne medecin_id existe et contient des valeurs
            $debug['sql_check'] = DB::select("SELECT COUNT(*) as count FROM information_schema.columns WHERE table_name = 'patients' AND column_name = 'medecin_id'");
            
            if (!empty($debug['sql_check']) && $debug['sql_check'][0]->count > 0) {
                $debug['medecin_id_values'] = DB::select("SELECT medecin_id, COUNT(*) as count FROM patients GROUP BY medecin_id");
            }
            
            // Méthode 4: Vérifier si les patients sont liés via une autre table
            $tables = DB::select("SHOW TABLES LIKE '%patient%'");
            $debug['related_tables'] = $tables;

            // Méthode 5: Vérifier si d'autres médecins ont des patients
            $otherMedecins = Medecin::where('id', '!=', $medecin->id)->limit(5)->get();
            $debug['other_medecins'] = [];
            
            foreach ($otherMedecins as $m) {
                $patientCount = Patient::where('medecin_id', $m->id)->count();
                $debug['other_medecins'][] = [
                    'id' => $m->id,
                    'patient_count' => $patientCount
                ];
            }
        }

        return response()->json($debug);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medecin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixPatientsController extends Controller
{
    public function assignPatientsToDoctor()
    {
        // Vérifier si l'utilisateur est connecté et est un médecin
        if (!auth()->check() || auth()->user()->role !== 'MEDECIN') {
            return response()->json(['error' => 'Utilisateur non autorisé'], 403);
        }

        // Trouver le médecin correspondant à l'utilisateur connecté
        $medecin = Medecin::where('utilisateur_id', auth()->id())->first();
        
        if (!$medecin) {
            return response()->json(['error' => 'Aucun enregistrement de médecin trouvé pour cet utilisateur'], 404);
        }

        // Vérifier s'il y a des patients sans médecin
        $patientsWithoutDoctor = Patient::whereNull('medecin_id')->limit(5)->get();
        
        if ($patientsWithoutDoctor->isEmpty()) {
            // Si aucun patient n'existe sans médecin, créons-en quelques-uns
            $newPatients = [];
            
            // Créer 5 patients de test
            for ($i = 1; $i <= 5; $i++) {
                // Créer un utilisateur pour chaque patient
                $user = User::create([
                    'nom' => 'Patient',
                    'prenom' => 'Test ' . $i,
                    'email' => 'patient.test' . $i . '@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'PATIENT'
                ]);
                
                // Créer le patient associé
                $patient = Patient::create([
                    'utilisateur_id' => $user->id,
                    'numeroPatient' => 'PT' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'nom' => 'Patient',
                    'prenom' => 'Test ' . $i,
                    'date_naissance' => '1990-01-01',
                    'sexe' => ($i % 2 == 0) ? 'F' : 'M',
                    'telephone' => '77' . rand(1000000, 9999999),
                    'email' => 'patient.test' . $i . '@example.com',
                    'adresse' => 'Adresse de test ' . $i,
                    'groupe_sanguin' => ['A+', 'B+', 'O+', 'AB+', 'O-'][rand(0, 4)]
                ]);
                
                // Assigner le patient au médecin
                DB::table('patients')
                    ->where('id', $patient->id)
                    ->update(['medecin_id' => $medecin->id]);
                
                $newPatients[] = $patient;
            }
            
            return response()->json([
                'success' => true,
                'message' => '5 nouveaux patients ont été créés et assignés au médecin',
                'patients' => $newPatients
            ]);
        } else {
            // Assigner les patients existants au médecin
            foreach ($patientsWithoutDoctor as $patient) {
                DB::table('patients')
                    ->where('id', $patient->id)
                    ->update(['medecin_id' => $medecin->id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => count($patientsWithoutDoctor) . ' patients existants ont été assignés au médecin',
                'patients' => $patientsWithoutDoctor
            ]);
        }
    }
}

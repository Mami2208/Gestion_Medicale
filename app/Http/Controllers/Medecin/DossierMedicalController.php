<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\DossierMedical;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DossierMedicalController extends Controller
{
    public function create()
    {
        return view('medecin.historique_medical.create');
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs,email',
                'telephone' => 'required|string|max:20',
                'date_naissance' => 'required|date',
                'genre' => 'required|in:M,F,A',
                'adresse' => 'nullable|string|max:255',
                'groupe_sanguin' => 'nullable|string|max:3',
                'mot_de_passe' => 'required|string|min:8|confirmed',
            ], [
                'email.unique' => 'Cet email est déjà utilisé.',
                'mot_de_passe.confirmed' => 'Les mots de passe ne correspondent pas.',
                'required' => 'Le champ :attribute est obligatoire.',
                'date' => 'Le champ :attribute doit être une date valide.',
                'email' => 'Le champ :attribute doit être une adresse email valide.',
                'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
                'in' => 'La valeur sélectionnée pour :attribute n\'est pas valide.',
                'confirmed' => 'Les champs :attribute ne correspondent pas.',
            ]);

            DB::beginTransaction();

            // Créer l'utilisateur
            $utilisateur = Utilisateur::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'mot_de_passe' => Hash::make($validated['mot_de_passe']),
                'role' => 'PATIENT',
                'date_naissance' => $validated['date_naissance'],
                'sexe' => $validated['genre'],
                'adresse' => $validated['adresse'],
                'statut' => 'ACTIF'
            ]);

            // Générer le numéro de patient
            $numeroPatient = 'PAT-' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);

            // Créer le patient
            $patient = Patient::create([
                'utilisateur_id' => $utilisateur->id,
                'numeroPatient' => $numeroPatient,
                'adresse' => $validated['adresse']
            ]);

            // Créer le dossier médical
            $dossier = DossierMedical::create([
                'patient_id' => $patient->id,
                'numero_dossier' => 'DOSS-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT),
                'groupe_sanguin' => $validated['groupe_sanguin']
            ]);

            DB::commit();

            return redirect()->route('medecin.historique-medical.show', $dossier->id)
                ->with('success', 'Dossier médical créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du dossier médical: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du dossier médical.']);
        }
    }

    public function show($id)
    {
        $dossier = DossierMedical::with([
            'patient.utilisateur',
            'historiques',
            'examens',
            'prescriptions'
        ])->findOrFail($id);

        return view('medecin.dossiers.show', compact('dossier'));
    }
}

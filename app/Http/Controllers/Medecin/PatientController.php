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

class PatientController extends Controller
{
    public function index()
    {
        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        // Récupérer les IDs des patients associés au médecin via les dossiers
        $patientsIds = $medecin->dossiers()->pluck('patient_id');
        
        $query = Patient::with(['utilisateur', 'dossierMedical'])
            ->join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->whereIn('patients.id', $patientsIds)
            ->select('patients.*')
            ->orderBy('utilisateurs.nom', 'asc')
            ->orderBy('utilisateurs.prenom', 'asc');

        // Ajouter les filtres si présents
        if (request('search')) {
            $query->where(function ($q) {
                $q->where('utilisateurs.nom', 'like', '%' . request('search') . '%')
                    ->orWhere('utilisateurs.prenom', 'like', '%' . request('search') . '%')
                    ->orWhere('utilisateurs.email', 'like', '%' . request('search') . '%');
            });
        }

        if (request('status')) {
            $query->where('patients.statut', request('status'));
        }

        $patients = $query->paginate(10);

        return view('medecin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('medecin.patients.create');
    }

    public function store(Request $request)
    {
        try {
            // Vérifier si l'email existe déjà
            if (Utilisateur::where('email', $request->email)->exists()) {
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'Cet email est déjà utilisé par un autre utilisateur.']);
            }

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
            DossierMedical::create([
                'patient_id' => $patient->id,
                'numero_dossier' => 'DOSS-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT),
                'groupe_sanguin' => $validated['groupe_sanguin']
            ]);

            DB::commit();

            return redirect()->route('medecin.patients.index')
                ->with('success', 'Patient créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log détaillé de l'erreur
            Log::error('Erreur lors de la création du patient: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Retourner avec les erreurs détaillées
            $errorDetails = [
                'general' => 'Une erreur est survenue lors de la création du patient.',
                'details' => $e->getMessage()
            ];
            
            return back()
                ->withInput()
                ->withErrors($errorDetails);
        }
    }

    public function show($id)
    {
        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        // Vérifier que le patient appartient bien au médecin via les dossiers
        $patient = Patient::whereHas('dossiers', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->with([
                'utilisateur',
                'dossierMedical',
                'dossierMedical.historiques',
                'dossierMedical.examens',
                'dossierMedical.prescriptions'
            ])
            ->findOrFail($id);

        return view('medecin.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        // Vérifier que le patient appartient bien au médecin via les dossiers
        $patient = Patient::whereHas('dossiers', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->findOrFail($id);

        return view('medecin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        // Log des données reçues
        Log::info('Début de la mise à jour du patient', [
            'patient_id' => $id,
            'donnees_recues' => $request->all(),
            'utilisateur' => auth()->user() ? auth()->user()->id : 'non connecté'
        ]);

        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            Log::warning('Tentative d\'accès non autorisée à la mise à jour d\'un patient', [
                'utilisateur_id' => auth()->user() ? auth()->user()->id : null,
                'patient_id' => $id
            ]);
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        try {
            // Vérifier que le patient appartient bien au médecin via les dossiers
            $patient = Patient::whereHas('dossiers', function($query) use ($medecin) {
                    $query->where('medecin_id', $medecin->id);
                })
                ->findOrFail($id);

            Log::info('Validation des données du formulaire', [
                'patient_id' => $patient->id,
                'utilisateur_id' => $patient->utilisateur_id
            ]);

            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $patient->utilisateur_id,
                'telephone' => 'required|string|max:20',
                'date_naissance' => 'required|date',
                'sexe' => 'required|in:M,F',
                'adresse' => 'required|string|max:255',
            ]);

            Log::info('Données validées avec succès', $validatedData);

            DB::beginTransaction();

            // Mettre à jour l'utilisateur
            $userUpdate = $patient->utilisateur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'date_naissance' => $request->date_naissance,
                'sexe' => $request->sexe,
                'adresse' => $request->adresse
            ]);

            Log::info('Mise à jour de l\'utilisateur', [
                'utilisateur_id' => $patient->utilisateur_id,
                'reussi' => $userUpdate
            ]);

            // Mettre à jour le patient
            $patientUpdate = $patient->update([
                'adresse' => $request->adresse
            ]);

            Log::info('Mise à jour du patient', [
                'patient_id' => $patient->id,
                'reussi' => $patientUpdate
            ]);

            DB::commit();

            Log::info('Patient mis à jour avec succès', [
                'patient_id' => $patient->id,
                'utilisateur_id' => $patient->utilisateur_id
            ]);

            return redirect()->route('medecin.patients.index')
                ->with('success', 'Patient mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la mise à jour du patient: ' . $e->getMessage(), [
                'fichier' => $e->getFile(),
                'ligne' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du patient. Détails : ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        try {
            DB::beginTransaction();
            
            // Vérifier que le patient appartient bien au médecin via les dossiers
            $patient = Patient::whereHas('dossiers', function($query) use ($medecin) {
                    $query->where('medecin_id', $medecin->id);
                })
                ->with(['dossierMedical', 'utilisateur'])
                ->findOrFail($id);

            // Supprimer le dossier médical s'il existe
            if ($patient->dossierMedical) {
                $patient->dossierMedical->delete();
            }

            // Supprimer le patient
            $patient->delete();

            // Supprimer l'utilisateur
            if ($patient->utilisateur) {
                $patient->utilisateur->delete();
            }

            DB::commit();

            return redirect()->route('medecin.patients.index')
                ->with('success', 'Patient supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du patient: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du patient.');
        }
    }

    public function dossier($id)
    {
        // Récupérer le médecin connecté
        $medecin = auth()->user()->medecinRelation;
        
        if (!$medecin) {
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        // Vérifier que le patient appartient bien au médecin via les dossiers
        $patient = Patient::whereHas('dossiers', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->with(['dossierMedical'])
            ->findOrFail($id);

        $dossier = DossierMedical::where('patient_id', $patient->id)
            ->with(['consultations', 'examens', 'prescriptions', 'images'])
            ->first();

        if (!$dossier) {
            return redirect()->route('medecin.patients.show', $patient->id)
                ->with('error', 'Le dossier médical n\'existe pas.');
        }

        return view('medecin.patients.dossier', compact('patient', 'dossier'));
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Dossiers_Medicaux;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Utilisateur;
use App\Models\Consultation;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Notifications\NouveauDossierMedical;

class SecretaireDossierMedicalController extends Controller
{
    /**
     * Afficher la liste des dossiers médicaux
     */
    public function index()
    {
        $dossiers = Dossiers_Medicaux::with(['patient', 'medecin'])->latest()->paginate(10);
        return view('secretaire.dossiers-medicaux.index', compact('dossiers'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau dossier médical
     */
    public function create()
    {
        $patients = Patient::with('utilisateur')->get();
        $medecins = Medecin::with('utilisateur')->get();
        return view('secretaire.dossiers-medicaux.create', compact('patients', 'medecins'));
    }

    /**
     * Enregistrer un nouveau dossier médical
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Valider les données du formulaire
            $validated = $request->validate([
                // Champs du patient
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'date_naissance' => 'required|date',
                'sexe' => 'required|in:H,F',
                'telephone' => 'required|string|max:20',
                'email' => 'required|email|unique:utilisateurs,email',
                'adresse' => 'required|string|max:255',
                'groupe_sanguin' => 'nullable|string|max:10',
                'antecedents_medicaux' => 'nullable|string',
                'allergies' => 'nullable|string',
                'traitements_en_cours' => 'nullable|string',
                
                // Champs du dossier médical
                'medecin_id' => 'required|exists:medecins,id',
                'motif_consultation' => 'required|string|max:255',
                'taille' => 'nullable|numeric|min:0|max:300',
                'poids' => 'nullable|numeric|min:0|max:500',
                'observations' => 'nullable|string',
            ]);
            
            // Créer un nouvel utilisateur
            $user = new Utilisateur();
            $user->nom = $validated['nom'];
            $user->prenom = $validated['prenom'];
            $user->email = $validated['email'];
            
            // Générer un mot de passe basé sur la date de naissance
            $password = $validated['date_naissance'] . rand(1000, 9999);
            $user->mot_de_passe = bcrypt($password);
            
            $user->telephone = $validated['telephone'];
            $user->adresse = $validated['adresse'];
            $user->date_naissance = $validated['date_naissance'];
            $user->sexe = $validated['sexe'];
            $user->role = 'PATIENT';
            
            // Sauvegarder l'utilisateur
            if (!$user->save()) {
                throw new \Exception('Échec de la création de l\'utilisateur');
            }

            // Créer le patient associé
            $patient = new Patient();
            $patient->utilisateur_id = $user->id;
            $patient->numeroPatient = 'PAT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $patient->groupe_sanguin = $validated['groupe_sanguin'] ?? null;
            $patient->antecedents_medicaux = $validated['antecedents_medicaux'] ?? null;
            $patient->allergies = $validated['allergies'] ?? null;
            $patient->traitements_en_cours = $validated['traitements_en_cours'] ?? null;
            
            if (!$patient->save()) {
                throw new \Exception('Échec de la création du patient');
            }

            // Générer un numéro de dossier unique
            $numeroDossier = 'DM-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $tentatives = 0;
            $maxTentatives = 5;
            
            // Vérifier si le numéro existe déjà et en générer un nouveau si nécessaire
            while (Dossiers_Medicaux::where('numero_dossier', $numeroDossier)->exists() && $tentatives < $maxTentatives) {
                $numeroDossier = 'DM-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $tentatives++;
            }
            
            // Si on a dépassé le nombre de tentatives, utiliser un timestamp
            if ($tentatives >= $maxTentatives) {
                $numeroDossier = 'DM-' . date('YmdHis') . '-' . strtoupper(Str::random(4));
                Log::warning('Utilisation d\'un numéro basé sur le timestamp après échec de génération unique');
            }
            
            // S'assurer que le numéro n'est pas vide
            if (empty($numeroDossier)) {
                throw new \Exception('Impossible de générer un numéro de dossier valide');
            }
            
            Log::info('Numéro de dossier généré: ' . $numeroDossier);
            
            // Créer le dossier médical
            $dossier = new Dossiers_Medicaux();
            $dossier->patient_id = $patient->id;
            $dossier->medecin_id = $validated['medecin_id'];
            $dossier->numero_dossier = $numeroDossier;
            $dossier->motif_consultation = $validated['motif_consultation'];
            $dossier->taille = $validated['taille'] ?? null;
            $dossier->poids = $validated['poids'] ?? null;
            $dossier->observations = $validated['observations'] ?? null;
            $dossier->statut = 'ACTIF';
            
            if (!$dossier->save()) {
                throw new \Exception('Échec de la création du dossier médical');
            }

            // Notifier le médecin
            $medecin = Medecin::find($validated['medecin_id']);
            if ($medecin && $medecin->utilisateur) {
                $medecin->utilisateur->notify(new NouveauDossierMedical($dossier));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('secretaire.dossiers-medicaux.show', $dossier->id),
                'message' => 'Dossier médical créé avec succès.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du dossier médical: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du dossier médical: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un dossier médical spécifique
     */
    public function show($id)
    {
        $dossier = Dossiers_Medicaux::with([
            'patient', 
            'patient.utilisateur',
            'medecin', 
            'medecin.utilisateur',
            'consultations',
            'consultations.medecin.utilisateur',
            'prescriptions',
            'examens',
            'images',
            'historiques'
        ])->findOrFail($id);
        
        return view('secretaire.dossiers-medicaux.show', compact('dossier'));
    }

    /**
     * Afficher le formulaire de modification d'un dossier médical
     */
    public function edit($id)
    {
        $dossier = DossierMedical::findOrFail($id);
        $patients = Patient::with('utilisateur')->get();
        $medecins = Medecin::with('utilisateur')->get();
        return view('secretaire.dossiers-medicaux.edit', compact('dossier', 'patients', 'medecins'));
    }

    /**
     * Mettre à jour un dossier médical
     */
    public function update(Request $request, $id)
    {
        $dossier = DossierMedical::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'numero_dossier' => 'required|string|max:50|unique:dossiers_medicaux,numero_dossier,' . $dossier->id,
            'date_creation' => 'required|date',
            'groupe_sanguin' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'antecedents_medicaux' => 'nullable|string',
            'traitements_en_cours' => 'nullable|string',
            'observations' => 'nullable|string',
            'taille' => 'nullable|numeric|min:0|max:300',
            'poids' => 'nullable|numeric|min:0|max:500',
            'motif_consultation' => 'nullable|string|max:255',
        ]);

        try {
            $dossier->update($validated);
            return redirect()->route('secretaire.dossiers-medicaux.show', $dossier->id)
                ->with('success', 'Dossier médical mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du dossier médical: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du dossier médical.');
        }
    }

    /**
     * Supprimer un dossier médical
     */
    public function destroy($id)
    {
        try {
            $dossier = DossierMedical::findOrFail($id);
            $dossier->delete();
            return redirect()->route('secretaire.dossiers-medicaux.index')
                ->with('success', 'Dossier médical supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du dossier médical: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du dossier médical.');
        }
    }
}

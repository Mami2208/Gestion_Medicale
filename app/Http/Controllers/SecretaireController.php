<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\Medecin;
use App\Models\Infirmier;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SecretaireController extends Controller
{
    /**
     * Affiche le tableau de bord de la secrétaire
     */
    public function dashboard()
    {
        // Vérification de l'authentification
        if (!auth()->check()) {
            \Log::error('Tentative d\'accès non authentifiée au tableau de bord secrétaire');
            return redirect('/login')->with('error', 'Veuillez vous connecter.');
        }
        
        $user = auth()->user();
        $role = $user->role;
        
        // Logs de débogage
        \Log::info('=== ACCÈS AU DASHBOARD SECRÉTAIRE ===');
        \Log::info('Utilisateur: ' . $user->email);
        \Log::info('Rôle: ' . $role);
        \Log::info('Type de rôle: ' . gettype($role));
        \Log::info('Longueur du rôle: ' . strlen($role));
        \Log::info('Session: ' . json_encode(session()->all()));
        \Log::info('Headers: ' . json_encode(request()->header()));
        
        // Vérification du rôle
        if (trim($role) !== 'SECRETAIRE') {
            \Log::error('Tentative d\'accès non autorisée au tableau de bord secrétaire par l\'utilisateur: ' . $user->email . ' (Rôle: ' . $role . ')' . ' (Type: ' . gettype($role) . ')' . ' (Longueur: ' . strlen($role) . ')');
            return redirect('/')->with('error', 'Accès non autorisé. Rôle requis: SECRETAIRE. Votre rôle: ' . $role);
        }
        \Log::info('=== ACCÈS AU DASHBOARD SECRÉTAIRE ===');
        \Log::info('Utilisateur connecté: ' . auth()->user()->email);
        \Log::info('Rôle: ' . auth()->user()->role);
        
        try {
            // Récupérer les statistiques
            $stats = [
                'patients' => Patient::count(),
                'rendez_vous_aujourdhui' => RendezVous::whereDate('date_rendez_vous', today())->count(),
                'dossiers_actifs' => DossierMedical::where('statut', 'ACTIF')->count(),
                'medecins' => Medecin::count()
            ];

            // Récupérer les rendez-vous du jour avec les relations nécessaires
            $rendezVousAujourdHui = RendezVous::with([
                    'patient' => function($query) {
                        $query->select('id', 'nom', 'prenom');
                    },
                    'medecin' => function($query) {
                        $query->with(['utilisateur' => function($q) {
                            $q->select('id', 'nom', 'prenom');
                        }]);
                    }
                ])
                ->whereDate('date_rendez_vous', today())
                ->orderBy('date_rendez_vous')
                ->orderBy('heure_debut')
                ->get();

            // Récupérer les derniers dossiers créés avec les relations nécessaires
            $derniersDossiers = DossierMedical::with([
                    'patient' => function($query) {
                        $query->select('id', 'nom', 'prenom');
                    },
                    'medecin' => function($query) {
                        $query->with(['utilisateur' => function($q) {
                            $q->select('id', 'nom', 'prenom');
                        }]);
                    }
                ])
                ->latest()
                ->take(5)
                ->get();

            return view('secretaire.dashboard', compact('stats', 'rendezVousAujourdHui', 'derniersDossiers'));
            
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@dashboard: ' . $e->getMessage());
            logger()->error($e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors du chargement du tableau de bord.');
        }
    }
    
    /**
     * Affiche la liste des dossiers médicaux
     */
    public function dossiers(Request $request)
    {
        try {
            $query = DossierMedical::with(['patient', 'medecin']);
            
            // Filtres de recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('numero_dossier', 'like', "%{$search}%")
                      ->orWhereHas('patient', function($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                      });
                });
            }

            $dossiers = $query->latest()->paginate(15);
            
            return view('secretaire.dossiers.index', compact('dossiers'));
            
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@dossiers: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement des dossiers médicaux.');
        }
    }

    /**
     * Affiche le formulaire de création d'un dossier médical
     */
    public function createDossier()
    {
        try {
            $patients = Patient::orderBy('nom')->get();
            $medecins = Medecin::with('user')->get()->map(function($medecin) {
                return [
                    'id' => $medecin->id,
                    'nom_complet' => $medecin->user->nom . ' ' . $medecin->user->prenom
                ];
            });
            
            return view('secretaire.dossiers.create', compact('patients', 'medecins'));
            
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@createDossier: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement du formulaire de création.');
        }
    }

    /**
     * Enregistre un nouveau dossier médical
     */
    // Les méthodes showDossier, editDossier, updateDossier et deleteDossier sont définies plus bas dans le contrôleur
    // avec une implémentation plus complète et une meilleure gestion des erreurs
    
    /**
     * @deprecated Utilisez la méthode storeDossierComplet
     */
    public function storeDossier(Request $request)
    {
        return $this->storeDossierComplet($request);
    }

    /**
     * Affiche les détails d'un dossier médical
     */
    public function showDossier($id)
    {
        try {
            $dossier = DossierMedical::with(['patient', 'medecin.user', 'consultations', 'consultations.prescriptions'])
                ->findOrFail($id);
                
            return view('secretaire.dossiers.show', compact('dossier'));
            
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@showDossier: ' . $e->getMessage());
            return back()->with('error', 'Dossier médical non trouvé ou erreur lors du chargement.');
        }
    }

    /**
     * Affiche le formulaire de modification d'un dossier médical
     */
    public function editDossier($id)
    {
        try {
            $dossier = DossierMedical::findOrFail($id);
            $patients = Patient::orderBy('nom')->get();
            $medecins = Medecin::with('user')->get()->map(function($medecin) {
                return [
                    'id' => $medecin->id,
                    'nom_complet' => $medecin->user->nom . ' ' . $medecin->user->prenom
                ];
            });
            
            return view('secretaire.dossiers.edit', compact('dossier', 'patients', 'medecins'));
            
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@editDossier: ' . $e->getMessage());
            return back()->with('error', 'Dossier médical non trouvé ou erreur lors du chargement.');
        }
    }

    /**
     * Met à jour un dossier médical
     */
    public function updateDossier(Request $request, $id)
    {
        try {
            $dossier = DossierMedical::findOrFail($id);
            
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'medecin_id' => 'required|exists:medecins,id',
                'numero_dossier' => 'required|string|max:50|unique:dossiers_medicaux,numero_dossier,' . $dossier->id,
                'date_creation' => 'required|date',
                'observations' => 'nullable|string',
                'antecedents' => 'nullable|string',
                'antecedents_medicaux' => 'nullable|array',
                'allergies' => 'nullable|array',
                'groupe_sanguin' => 'nullable|string|max:10',
                'taille' => 'nullable|numeric|min:0|max:250',
                'poids' => 'nullable|numeric|min:0|max:300',
                'motif_consultation' => 'nullable|string',
                'traitements_en_cours' => 'nullable|array',
                'statut' => 'required|in:actif,archive',
            ]);

            $dossier->update($validated);

            return redirect()->route('secretaire.dossiers-medicaux.show', $dossier->id)
                ->with('success', 'Le dossier médical a été mis à jour avec succès.');
                
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@updateDossier: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour du dossier médical.');
        }
    }

    /**
     * Supprime un dossier médical
     */
    public function deleteDossier($id)
    {
        try {
            $dossier = DossierMedical::findOrFail($id);
            $dossier->delete();
            
            return redirect()->route('secretaire.dossiers-medicaux.index')
                ->with('success', 'Le dossier médical a été supprimé avec succès.');
                
        } catch (\Exception $e) {
            logger()->error('Erreur dans SecretaireController@deleteDossier: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du dossier médical.');
        }
    }

    /**
     * Récupère les statistiques pour le tableau de bord
     */
    protected function getStats()
    {
        // Statistiques des patients
        $patientsHommes = Patient::where('sexe', 'M')->count();
        $patientsFemmes = Patient::where('sexe', 'F')->count();
        $totalPatients = $patientsHommes + $patientsFemmes;
        
        // Statistiques des rendez-vous
        $rdvAujourdhui = RendezVous::whereDate('date_rendez_vous', today())->count();
        $totalRdv = RendezVous::count();
        
        // Statistiques des dossiers
        $dossiersActifs = DossierMedical::where('statut', 'ACTIF')->count();
        $totalDossiers = DossierMedical::count();
        
        // Statistiques des médecins
        $totalMedecins = Medecin::count();

        return [
            'patients' => $totalPatients,
            'patients_hommes' => $patientsHommes,
            'patients_femmes' => $patientsFemmes,
            'rendez_vous_aujourdhui' => $rdvAujourdhui,
            'rendez_vous_total' => $totalRdv,
            'notifications_non_lues' => auth()->user()->unreadNotifications->count()
        ];
    }

    /**
     * Enregistre un nouveau dossier médical
     * Gère à la fois la création d'un nouveau patient et/ou d'un nouveau dossier médical
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function storeDossierComplet(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Informations du patient/utilisateur
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:H,F',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:utilisateurs,email',
            'groupe_sanguin' => 'nullable|string|max:5',
            'mot_de_passe' => 'nullable|string|min:6',
            
            // Informations du dossier médical
            'medecin_id' => 'required|exists:medecins,id',
            'numero_dossier' => 'nullable|string|max:50|unique:dossiers_medicaux,numero_dossier',
            'motif_consultation' => 'required|string',
            'antecedents' => 'nullable|string',
            'antecedents_medicaux' => 'nullable|array',
            'allergies' => 'nullable|array',
            'groupe_sanguin' => 'nullable|string|max:10',
            'taille' => 'nullable|numeric|min:0|max:250',
            'poids' => 'nullable|numeric|min:0|max:300',
            'traitements_chroniques' => 'nullable|array',
            'statut' => 'required|in:actif,archivé,en attente',
            'observations' => 'nullable|string',
        ]);

        \Log::info('Début création dossier médical');

        DB::beginTransaction();

        try {
            // 1. Vérifier si un utilisateur avec cet email existe déjà
            $utilisateur = \App\Models\Utilisateur::where('email', $validated['email'])->first();
            
            if (!$utilisateur) {
                // Si l'utilisateur n'existe pas, le créer
                $utilisateur = new \App\Models\Utilisateur();
                $utilisateur->nom = $validated['nom'];
                $utilisateur->prenom = $validated['prenom'];
                $utilisateur->email = $validated['email'];
                $utilisateur->telephone = $validated['telephone'];
                $utilisateur->date_naissance = $validated['date_naissance'];
                $utilisateur->sexe = $validated['sexe'];
                $utilisateur->role = 'PATIENT';
                $utilisateur->statut = 'ACTIF';
                
                // Générer un mot de passe aléatoire (basé sur la date de naissance et un nombre aléatoire)
                $password = $validated['date_naissance'] . rand(1000, 9999);
                $utilisateur->mot_de_passe = bcrypt($password);
                \Log::info('Mot de passe généré pour le nouvel utilisateur: ' . $password);
                
                $utilisateur->save();
                \Log::info('Nouvel utilisateur créé avec ID: ' . $utilisateur->id);
            } else {
                \Log::info('Utilisateur existant trouvé avec ID: ' . $utilisateur->id);
            }
            
            // 2. Vérifier si un patient avec cet utilisateur existe déjà
            $patient = Patient::where('utilisateur_id', $utilisateur->id)->first();
            
            if (!$patient) {
                // Si le patient n'existe pas, le créer
                $patient = new Patient();
                $patient->utilisateur_id = $utilisateur->id;
                $patient->numeroPatient = 'P' . date('Ymd') . rand(1000, 9999); // Numéro de patient unique
                $patient->adresse = $validated['adresse'] ?? null;
                $patient->groupe_sanguin = $validated['groupe_sanguin'] ?? null;
                $patient->save();
                \Log::info('Nouveau patient créé avec ID: ' . $patient->id);
            } else {
                // Mettre à jour les informations du patient existant
                $patient->adresse = $validated['adresse'] ?? $patient->adresse;
                $patient->groupe_sanguin = $validated['groupe_sanguin'] ?? $patient->groupe_sanguin;
                $patient->save();
                \Log::info('Patient existant mis à jour avec ID: ' . $patient->id);
            }

            // 3. Créer le dossier médical
            $dossier = new DossierMedical();
            $dossier->patient_id = $patient->id;
            $dossier->medecin_id = $validated['medecin_id'];
            $dossier->numero_dossier = $validated['numero_dossier'] ?? ('DM-' . date('Ymd') . '-' . rand(1000, 9999));
            $dossier->motif_consultation = $validated['motif_consultation'];
            $dossier->antecedents = $validated['antecedents'] ?? null;
            $dossier->antecedents_medicaux = $validated['antecedents_medicaux'] ?? null;
            $dossier->allergies = $validated['allergies'] ?? null;
            $dossier->groupe_sanguin = $validated['groupe_sanguin'] ?? null;
            $dossier->taille = $validated['taille'] ?? null;
            $dossier->poids = $validated['poids'] ?? null;
            $dossier->traitements_chroniques = $validated['traitements_chroniques'] ?? null;
            $dossier->observations = $validated['observations'] ?? null;
            $dossier->statut = $validated['statut'];
            $dossier->date_creation = now();
            $dossier->save();
            
            \Log::info('Nouveau dossier médical créé avec ID: ' . $dossier->id);

            // Création d'une notification pour le médecin
            $medecin = Medecin::find($validated['medecin_id']);
            if ($medecin && $medecin->user) {
                $medecin->user->notify(new \App\Notifications\DossierMedicalCree($dossier));
            }

            DB::commit();
            
            // Message de succès détaillé
            $message = 'Opération réussie! ';
            $message .= 'Utilisateur: ' . $utilisateur->nom . ' ' . $utilisateur->prenom;
            $message .= ', Patient ID: ' . $patient->id;
            $message .= ', Dossier ID: ' . $dossier->id;
            $message .= ', Numéro dossier: ' . $dossier->numero_dossier;
            
            return redirect()->route('secretaire.dossiers-medicaux.show', $dossier->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur dans SecretaireController@storeDossierComplet: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création du dossier médical: ' . $e->getMessage());
        }
    }



    public function patients()
    {
        $patients = Patient::with(['dossiers', 'utilisateur'])
            ->withCount('dossiers')
            ->latest()
            ->paginate(10);
        return view('secretaire.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        return view('secretaire.patients.create');
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'sexe' => 'required|in:H,F',
            'groupe_sanguin' => 'nullable|string|max:3',
        ]);

        Patient::create($validated);

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient créé avec succès');
    }

    public function showPatient($id)
    {
        $patient = Patient::with(['dossiers', 'utilisateur'])
            ->findOrFail($id);
        return view('secretaire.patients.show', compact('patient'));
    }

    public function editPatient($id)
    {
        $patient = Patient::findOrFail($id);
        return view('secretaire.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:H,F',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'code_postal' => 'nullable|string',
            'ville' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validated);

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient mis à jour avec succès');
    }

    public function deletePatient($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('secretaire.patients.index')
            ->with('success', 'Patient supprimé avec succès');
    }

    public function rendezVous()
    {
        $rendezVous = RendezVous::with(['patient', 'medecin'])
            ->orderBy('date_rendez_vous', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->paginate(10);
        
        return view('secretaire.rendez-vous.index', compact('rendezVous'));
    }

    public function createRendezVous()
    {
        $patients = Patient::all();
        $medecins = Medecin::all();
        return view('secretaire.rendez-vous.create', compact('patients', 'medecins'));
    }

    public function storeRendezVous(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_rendez_vous' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'motif' => 'required|string',
            'medecin_id' => 'required|exists:medecins,id'
        ]);

        DB::beginTransaction();

        try {
            // Créer le rendez-vous
            $rendezVous = RendezVous::create($validated);
            
            // Récupérer les informations du patient et du médecin pour la notification
            $patient = Patient::with('utilisateur')->find($validated['patient_id']);
            $medecin = Medecin::find($validated['medecin_id']);
            
            // Formater la date et l'heure pour une meilleure lisibilité
            $dateFormatee = \Carbon\Carbon::parse($validated['date_rendez_vous'])->format('d/m/Y');
            
            // Créer une notification pour le médecin
            if ($medecin) {
                Notification::create([
                    'title' => 'Nouveau rendez-vous planifié',
                    'message' => "Un nouveau rendez-vous a été planifié le {$dateFormatee} de {$validated['heure_debut']} à {$validated['heure_fin']} avec le patient {$patient->utilisateur->nom} {$patient->utilisateur->prenom}. Motif: {$validated['motif']}",
                    'dateEnvoi' => now(),
                    'typeLecture' => 0, // 0 = non lu
                    'medecin_id' => $validated['medecin_id']
                ]);
            }

            DB::commit();

            return redirect()->route('secretaire.rendez-vous.index')
                ->with('success', 'Rendez-vous créé avec succès et notification envoyée au médecin');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du rendez-vous: ' . $e->getMessage()]);
        }
    }

    public function showRendezVous($id)
    {
        $rendezVous = RendezVous::with('patient', 'medecin')->findOrFail($id);
        return view('secretaire.rendez-vous.show', compact('rendezVous'));
    }

    public function editRendezVous($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $patients = Patient::all();
        $medecins = Medecin::all();
        return view('secretaire.rendez-vous.edit', compact('rendezVous', 'patients', 'medecins'));
    }

    public function updateRendezVous(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_rendez_vous' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'motif' => 'required|string',
            'medecin_id' => 'required|exists:medecins,id'
        ]);

        DB::beginTransaction();

        try {
            // Récupérer le rendez-vous avant de le modifier pour vérifier si le médecin a changé
            $rendezVous = RendezVous::findOrFail($id);
            $ancienMedecinId = $rendezVous->medecin_id;
            
            // Mettre à jour le rendez-vous
            $rendezVous->update($validated);
            
            // Récupérer les informations du patient et du médecin pour la notification
            $patient = Patient::with('utilisateur')->find($validated['patient_id']);
            $dateFormatee = \Carbon\Carbon::parse($validated['date_rendez_vous'])->format('d/m/Y');
            
            // Si le médecin a changé, notifier les deux médecins
            if ($ancienMedecinId != $validated['medecin_id'] && $ancienMedecinId) {
                // Notifier l'ancien médecin
                Notification::create([
                    'title' => 'Rendez-vous modifié',
                    'message' => "Un rendez-vous avec le patient {$patient->utilisateur->nom} {$patient->utilisateur->prenom} a été transféré à un autre médecin.",
                    'dateEnvoi' => now(),
                    'typeLecture' => 0,
                    'medecin_id' => $ancienMedecinId
                ]);
            }
            
            // Notifier le médecin actuel (qu'il soit nouveau ou le même)
            Notification::create([
                'title' => 'Rendez-vous modifié',
                'message' => "Un rendez-vous a été modifié pour le {$dateFormatee} de {$validated['heure_debut']} à {$validated['heure_fin']} avec le patient {$patient->utilisateur->nom} {$patient->utilisateur->prenom}. Motif: {$validated['motif']}",
                'dateEnvoi' => now(),
                'typeLecture' => 0,
                'medecin_id' => $validated['medecin_id']
            ]);
            
            DB::commit();

            return redirect()->route('secretaire.rendez-vous.index')
                ->with('success', 'Rendez-vous mis à jour avec succès et notification envoyée au médecin');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du rendez-vous: ' . $e->getMessage()]);
        }
    }

    public function deleteRendezVous($id)
    {
        DB::beginTransaction();

        try {
            $rendezVous = RendezVous::findOrFail($id);
            $rendezVous->delete();

            DB::commit();

            return redirect()->route('secretaire.rendez-vous.index')
                ->with('success', 'Rendez-vous supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la suppression du rendez-vous']);
        }
    }

    public function notifications()
    {
        // Récupérer les paramètres de filtrage et de tri
        $type = request('type');
        $status = request('status');
        $search = request('search');
        $sort = request('sort', 'created_at');
        $direction = request('direction', 'desc');
        
        // Récupérer les notifications
        $query = auth()->user()->notifications();
        
        // Appliquer les filtres
        if ($type) {
            $query->whereRaw("JSON_EXTRACT(data, '$.type') = ?", [$type]);
        }
        
        if ($status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($status === 'unread') {
            $query->whereNull('read_at');
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(data, '$.title') LIKE ?", ['%'.$search.'%'])
                  ->orWhereRaw("JSON_EXTRACT(data, '$.message') LIKE ?", ['%'.$search.'%']);
            });
        }
        
        // Appliquer le tri
        $query->orderBy($sort, $direction);
        
        // Paginer les résultats
        $notifications = $query->paginate(10);
        
        // Statistiques des notifications
        $today_notifications = auth()->user()->notifications()
            ->whereDate('created_at', today())
            ->count();
        
        $sent_notifications = 0; // À implémenter avec un modèle spécifique pour les notifications envoyées
        
        return view('secretaire.notifications.index', compact(
            'notifications',
            'today_notifications',
            'sent_notifications'
        ));
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Afficher le formulaire de création de notification
     */
    public function createNotification()
    {
        // Récupérer les médecins et les patients pour les destinataires
        $medecins = \App\Models\Medecin::with('utilisateur')->get();
        $patients = Patient::with('utilisateur')->limit(20)->get();
        
        return view('secretaire.notifications.create', compact('medecins', 'patients'));
    }

    /**
     * Enregistrer une nouvelle notification
     */
    public function storeNotification(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|string|in:low,normal,high',
            'recipient_mode' => 'required|string|in:specific,filter',
            'recipient_ids' => 'required_if:recipient_mode,specific',
            'schedule_type' => 'required|string|in:now,later,relative',
            'scheduled_date' => 'required_if:schedule_type,later|nullable|date_format:d/m/Y',
            'scheduled_time' => 'required_if:schedule_type,later|nullable|date_format:H:i',
            'channels' => 'required|array',
            'channels.*' => 'in:app,email,sms'
        ]);
        
        // Traiter les destinataires
        if ($validated['recipient_mode'] === 'specific') {
            // Destinataires spécifiques
            $recipientIds = explode(',', $validated['recipient_ids']);
            
            foreach ($recipientIds as $id) {
                // Trouver l'utilisateur et envoyer la notification
                $user = \App\Models\User::find($id);
                if ($user) {
                    // Envoyer la notification
                    $this->sendNotificationToUser($user, $validated);
                }
            }
        } else {
            // Filtrer par critères
            $query = \App\Models\User::query();
            
            // Appliquer les filtres (à personnaliser selon vos besoins)
            if ($request->has('filter_medecin_id') && $request->filter_medecin_id) {
                // Exemple: tous les patients d'un médecin
                $query->whereHas('patient', function($q) use ($request) {
                    $q->where('medecin_id', $request->filter_medecin_id);
                });
            }
            
            if ($request->has('filter_rendez_vous') && $request->filter_rendez_vous) {
                // Exemple: patients avec rendez-vous aujourd'hui/demain/cette semaine
                $query->whereHas('rendezVous', function($q) use ($request) {
                    if ($request->filter_rendez_vous === 'today') {
                        $q->whereDate('date_rendez_vous', today());
                    } elseif ($request->filter_rendez_vous === 'tomorrow') {
                        $q->whereDate('date_rendez_vous', today()->addDay());
                    } elseif ($request->filter_rendez_vous === 'week') {
                        $q->whereBetween('date_rendez_vous', [today(), today()->addDays(7)]);
                    }
                });
            }
            
            // Envoyer à tous les utilisateurs filtrés
            $users = $query->get();
            foreach ($users as $user) {
                $this->sendNotificationToUser($user, $validated);
            }
        }
        
        return redirect()->route('secretaire.notifications.index')
            ->with('success', 'Notification(s) envoyée(s) avec succès.');
    }

    /**
     * Envoyer une notification à un utilisateur spécifique
     */
    protected function sendNotificationToUser($user, $data)
    {
        // Préparer les données de la notification
        $notificationData = [
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'priority' => $data['priority']
        ];
        
        // Si envoi immédiat
        if ($data['schedule_type'] === 'now') {
            // Envoyer via les canaux sélectionnés
            if (in_array('app', $data['channels'])) {
                $user->notify(new \App\Notifications\GeneralNotification($notificationData));
            }
            
            // Pour les autres canaux (email, sms), vous devrez implémenter les notifications correspondantes
            if (in_array('email', $data['channels']) && $user->email) {
                // Implémenter l'envoi par email
            }
            
            if (in_array('sms', $data['channels']) && $user->telephone) {
                // Implémenter l'envoi par SMS
            }
        } else {
            // Pour les notifications planifiées, vous devrez implémenter un système de planification
            // Vous pouvez utiliser Laravel Task Scheduling ou un système de files d'attente
        }
    }

    public function supprimerNotification($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification supprimée avec succès');
    }

    // Les méthodes showDossier, editDossier, updateDossier et deleteDossier sont déjà définies plus haut dans le contrôleur
    // avec une implémentation plus complète et une meilleure gestion des erreurs
    
    /**
     * Affiche le profil de la secrétaire connectée
     */
    public function profile()
    {
        $user = auth()->user();
        return view('secretaire.profile.index', compact('user'));
    }
    
    /**
     * Affiche le formulaire d'édition du profil
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('secretaire.profile.edit', compact('user'));
    }
    
    /**
     * Met à jour les informations du profil
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:utilisateurs,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Gestion de la photo de profil
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                // Supprimer l'ancienne photo si elle existe
                if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
                    @unlink(public_path('storage/' . $user->photo));
                }
                
                // Générer un nom unique pour l'image
                $photoName = time() . '_' . uniqid() . '.' . $request->photo->extension();
                
                // Enregistrer la nouvelle photo dans le dossier storage/app/public/profile-photos
                $request->photo->storeAs('profile-photos', $photoName, 'public');
                
                // Mettre à jour le champ photo dans la base de données
                $validated['photo'] = 'profile-photos/' . $photoName;
                
                // Journal de debug
                \Illuminate\Support\Facades\Log::info('Photo uploaded: ' . $photoName);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error uploading photo: ' . $e->getMessage());
                return back()->withErrors(['photo' => 'Erreur lors du téléchargement de la photo: ' . $e->getMessage()])->withInput();
            }
        }
        
        // Supprimer la photo du tableau validated car on l'a déjà traitée
        if (isset($validated['photo']) && $validated['photo'] instanceof \Illuminate\Http\UploadedFile) {
            unset($validated['photo']);
        }
        
        $user->update($validated);
        
        return redirect()->route('secretaire.profile')
            ->with('success', 'Profil mis à jour avec succès');
    }
    
    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Vérifier que le mot de passe actuel est correct
        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->mot_de_passe)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }
        
        $user->update([
            'mot_de_passe' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);
        
        return redirect()->route('secretaire.profile')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }
    
    /**
     * Recherche globale de patients, dossiers médicaux, rendez-vous
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Veuillez saisir un terme de recherche');
        }
        
        // Rechercher dans les patients via leur relation avec Utilisateur uniquement
        $patients = Patient::whereHas('utilisateur', function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('prenom', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('telephone', 'like', "%{$query}%");
            })
            ->with(['dossiers_medicaux', 'utilisateur'])
            ->get();
        
        // Rechercher dans les dossiers médicaux (uniquement dans les colonnes existantes)
        $dossiers = DossierMedical::where('numero_dossier', 'like', "%{$query}%")
            ->orWhere('motif_consultation', 'like', "%{$query}%")
            ->orWhere('observations', 'like', "%{$query}%")
            ->with(['patient.utilisateur', 'medecin.utilisateur'])
            ->get();
        
        // Rechercher dans les rendez-vous
        $rendezVous = RendezVous::whereHas('patient.utilisateur', function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('prenom', 'like', "%{$query}%");
            })
            ->orWhereHas('medecin.utilisateur', function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('prenom', 'like', "%{$query}%");
            })
            ->orWhere('motif', 'like', "%{$query}%")
            ->orWhere('notes', 'like', "%{$query}%")
            ->with(['patient.utilisateur', 'medecin.utilisateur'])
            ->get();
        return view('secretaire.search.results', compact('patients', 'dossiers', 'rendezVous', 'query'));
    }
    
    /**
     * Affiche la page d'attribution de patients aux infirmiers
     */
    public function showPatientAssignment()
    {
        // Version simplifiée pour le débogage
        return 'Test de la méthode showPatientAssignment - Si vous voyez ce message, la méthode fonctionne.';
        
        // Code original commenté pour le débogage
        /*
        // Récupérer tous les patients
        $patients = Patient::with(['utilisateur', 'infirmier.utilisateur'])->get();
        
        // Récupérer tous les infirmiers disponibles
        $infirmiers = \App\Models\Infirmier::with('utilisateur')->get();
        
        return view('secretaire.patients.assign', compact('patients', 'infirmiers'));
        */
    }
    
    /**
     * Attribue un patient à un infirmier
     */
    public function assignPatientToNurse(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'infirmier_id' => 'required|exists:infirmiers,id'
        ]);
        
        $patient = Patient::findOrFail($request->patient_id);
        $oldInfirmierId = $patient->infirmier_id;
        $patient->infirmier_id = $request->infirmier_id;
        $patient->save();
        
        // Créer une notification pour l'infirmier en utilisant la méthode du modèle
        $patient->notifyAssignmentToNurse($request->infirmier_id, $oldInfirmierId);
        
        // Journal d'activité
        if (method_exists(auth()->user(), 'logActivity')) {
            auth()->user()->logActivity(
                'patient_assignment',
                'Assignation du patient #' . $patient->id . ' à l\'infirmier #' . $request->infirmier_id,
                $patient
            );
        }
        
        // Récupérer l'infirmier pour le message de succès
        $infirmier = \App\Models\Infirmier::with('utilisateur')->find($request->infirmier_id);
        $nomInfirmier = $infirmier ? $infirmier->utilisateur->prenom . ' ' . $infirmier->utilisateur->nom : 'l\'infirmier';
        
        return redirect()->back()->with('success', 'Patient attribué avec succès à ' . $nomInfirmier);
    }
    
    /**
     * Attribue plusieurs patients à un infirmier
     */
    public function assignMultiplePatientsToNurse(Request $request)
    {
        $request->validate([
            'patient_ids' => 'required|array',
            'patient_ids.*' => 'exists:patients,id',
            'infirmier_id' => 'required|exists:infirmiers,id'
        ]);
        
        $infirmier = \App\Models\Infirmier::with('utilisateur')->find($request->infirmier_id);
        $patientsCount = count($request->patient_ids);
        
        // Récupérer les patients et notifier chacun individuellement
        $patients = Patient::whereIn('id', $request->patient_ids)->get();
        foreach($patients as $patient) {
            $oldInfirmierId = $patient->infirmier_id;
            $patient->infirmier_id = $request->infirmier_id;
            $patient->save();
            
            // Notifier individuellement
            $patient->notifyAssignmentToNurse($request->infirmier_id, $oldInfirmierId);
        }
        
        // Créer une notification globale pour l'infirmier en utilisant le système de notifications Laravel
        $infirmier->utilisateur->notify(new \App\Notifications\MultiplePatientAssigned([
            'title' => 'Patients assignés',
            'message' => $patientsCount . ' nouveau(x) patient(s) vous ont été assignés',
            'type' => 'MULTIPLE_ASSIGNMENT',
            'count' => $patientsCount
        ]));
        
        // Journal d'activité
        if (method_exists(auth()->user(), 'logActivity')) {
            auth()->user()->logActivity(
                'multiple_patient_assignment',
                'Assignation de ' . $patientsCount . ' patient(s) à l\'infirmier #' . $request->infirmier_id,
                null
            );
        }
        
        $nomInfirmier = $infirmier ? $infirmier->utilisateur->prenom . ' ' . $infirmier->utilisateur->nom : 'l\'infirmier';
        return redirect()->back()->with('success', $patientsCount . ' patient(s) attribué(s) avec succès à ' . $nomInfirmier);
    }
}

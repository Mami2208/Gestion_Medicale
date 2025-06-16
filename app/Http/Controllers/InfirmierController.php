<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Traitement;
use App\Models\Observation;
use App\Models\Alerte;
use App\Models\Infirmier;
use App\Models\Medicament;
use App\Models\DelegationAcces;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class InfirmierController extends Controller
{
    // Classe de contrôleur pour la gestion des fonctionnalités de l'infirmier
    
    /**
     * Récupère l'infirmier associé à l'utilisateur connecté
     *
     * @return \App\Models\Infirmier|null
     */
    private function getInfirmierFromAuth()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        if (!$user || $user->role !== 'INFIRMIER') {
            return null;
        }
        
        // Récupérer l'infirmier associé à cet utilisateur
        return Infirmier::where('utilisateur_id', $user->id)->first();
    }
    

    /**
     * Affiche le profil de l'infirmier
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile()
    {
        try {
            $infirmier = $this->getInfirmierFromAuth();
            if (!$infirmier) {
                return redirect()->route('login')->with('error', 'Accès non autorisé');
            }
            
            $user = $infirmier->utilisateur;
            return view('infirmier.profile.show', compact('infirmier', 'user'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'affichage du profil: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'affichage du profil.');
        }
    }
    
    /**
     * Affiche le formulaire de modification du profil
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editProfile()
    {
        try {
            $infirmier = $this->getInfirmierFromAuth();
            if (!$infirmier) {
                return redirect()->route('login')->with('error', 'Accès non autorisé');
            }
            
            $user = $infirmier->utilisateur;
            return view('infirmier.profile.edit', compact('infirmier', 'user'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'affichage du formulaire de modification du profil: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }
    
    /**
     * Met à jour les informations du profil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $infirmier = $this->getInfirmierFromAuth();
            if (!$infirmier) {
                return redirect()->route('login')->with('error', 'Accès non autorisé');
            }
            
            $user = $infirmier->utilisateur;
            
            // Valider les données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('utilisateurs', 'email')->ignore($user->id)
                ],
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string|max:255',
                'date_naissance' => 'nullable|date',
                'matricule' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('infirmiers', 'matricule')->ignore($infirmier->id)
                ],
                'specialite' => 'nullable|string|max:255',
            ]);
            
            // Démarrer une transaction pour s'assurer que toutes les mises à jour réussissent
            \DB::beginTransaction();
            
            try {
                // Mise à jour des informations de l'utilisateur
                $userData = [
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                ];
                
                // Ajouter les champs optionnels s'ils sont présents
                $optionalFields = ['telephone', 'adresse', 'date_naissance'];
                foreach ($optionalFields as $field) {
                    if (isset($validated[$field])) {
                        $userData[$field] = $validated[$field];
                    }
                }
                
                $user->update($userData);
                
                // Mise à jour des informations spécifiques à l'infirmier
                $infirmier->update([
                    'matricule' => $validated['matricule'],
                    'specialite' => $validated['specialite'] ?? null,
                ]);
                
                // Valider les modifications
                $user->save();
                $infirmier->save();
                
                // Tout s'est bien passé, on valide la transaction
                \DB::commit();
                
                return redirect()->route('infirmier.profile')
                    ->with('success', 'Votre profil a été mis à jour avec succès.');
                    
            } catch (\Exception $e) {
                // En cas d'erreur, on annule les modifications
                \DB::rollBack();
                throw $e; // Relancer l'exception pour qu'elle soit capturée par le bloc catch externe
            }
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du profil: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du profil. Veuillez réessayer.')
                ->withInput();
        }
    }
    
    /**
     * Affiche le tableau de bord de l'infirmier
     */
    public function dashboard()
    {
        // Récupérer l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }
        
        // Compter les patients assignés directement à cet infirmier
        $patientsCount = $infirmier->patients()->count();
        
        // Récupérer les délégations d'accès actives pour cet infirmier
        $delegations = \App\Models\DelegationAcces::where('infirmier_id', $infirmier->utilisateur_id)
            ->with([
                'patient.utilisateur',
                'medecin'
            ])
            ->where('date_fin', '>=', now())
            ->where('statut', 'active')
            ->orderBy('date_debut', 'desc')
            ->limit(10)
            ->get();
        
        // Compter les patients en soins critiques (avec priorité haute ou urgence)
        $patientsCritiques = $infirmier->patients()
            ->whereHas('traitements', function($query) {
                $query->whereIn('priorite', ['haute', 'urgence'])
                      ->where('statut', 'en_cours');
            })
            ->count();
            
        // Compter les soins prévus pour aujourd'hui
        $soinsAujourdhui = \App\Models\Traitement::whereHas('patient', function($q) use ($infirmier) {
                $q->where('infirmier_id', $infirmier->id);
            })
            ->whereDate('date_debut', '<=', now())
            ->whereDate('date_fin', '>=', now())
            ->where('statut', 'en_cours')
            ->count();
        
        // Statistiques pour le tableau de bord
        $stats = [
            'patients_suivis' => $patientsCount,
            'traitements_actifs' => $infirmier->traitements()->where('statut', 'en_cours')->count(),
            'patients_critiques' => $patientsCritiques,
            'soins_aujourdhui' => $soinsAujourdhui,
            'delegations_actives' => $delegations->count()
        ];

        return view('infirmier.dashboard', compact('stats', 'delegations'));
    }

    /**
     * Liste des patients suivis par l'infirmier
     */
    /**
     * Affiche la liste des patients suivis par l'infirmier
     * Inclut les patients assignés directement et ceux via délégation
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    /**
     * Affiche le formulaire de création d'une nouvelle observation
     *
     * @param int $patientId ID du patient
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createObservation(Request $request)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        // Récupérer l'ID du patient depuis la requête
        $patientId = $request->query('patient_id');
        
        if (!$patientId) {
            return back()->with('error', 'Aucun patient spécifié');
        }

        // Vérifier si l'infirmier a accès à ce patient
        $patient = Patient::find($patientId);
        
        if (!$patient) {
            return back()->with('error', 'Patient non trouvé');
        }
        
        // Vérifier si le patient est assigné directement ou via délégation
        $hasAccess = $this->checkPatientAccess($infirmier, $patientId);

        if (!$hasAccess) {
            return back()->with('error', 'Accès non autorisé à ce patient');
        }

        return view('infirmier.observations.create', [
            'patient' => $patient,
            'infirmier' => $infirmier
        ]);
    }

    /**
     * Enregistre une nouvelle observation pour un patient
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeObservation(Request $request)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        // Validation des données
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'contenu' => 'required|string',
            'date_observation' => 'required|date',
            'type_observation' => 'sometimes|in:observation,symptome,examen,suivi,autre',
            'est_important' => 'nullable|boolean'
        ]);

        // Vérifier si l'infirmier a accès à ce patient
        $hasAccess = $this->checkPatientAccess($infirmier, $validated['patient_id']);
        if (!$hasAccess) {
            return back()->with('error', 'Accès non autorisé à ce patient');
        }

        try {
            // Création de l'observation
            $observation = new Observation([
                'patient_id' => $validated['patient_id'],
                'infirmier_id' => $infirmier->utilisateur_id, // Utiliser utilisateur_id pour la relation
                'contenu' => $validated['contenu'],
                'date_observation' => $validated['date_observation'],
                'type' => $validated['type_observation'] ?? 'observation',
                'est_urgent' => $request->has('est_important') ? 1 : 0,
                'statut' => 'actif' // Utiliser des minuscules pour la cohérence
            ]);

            $observation->save();

            return redirect()
                ->route('infirmier.patients.show', $validated['patient_id'])
                ->with('success', 'Observation enregistrée avec succès.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de l\'observation: ' . $e->getMessage());
        }
    }

    /**
     * Vérifie si l'infirmier a accès au patient (directement ou via délégation)
     *
     * @param \App\Models\Infirmier $infirmier L'infirmier dont on vérifie les droits d'accès
     * @param int $patientId L'ID du patient à vérifier
     * @return bool Vrai si l'infirmier a accès, faux sinon
     */
    private function checkPatientAccess($infirmier, $patientId)
    {
        // Vérifier si le patient est directement assigné à l'infirmier
        $directAccess = $infirmier->patients()->where('patients.id', $patientId)->exists();
        
        // Vérifier si une délégation d'accès active existe
        $delegatedAccess = \App\Models\DelegationAcces::where('infirmier_id', $infirmier->utilisateur_id)
            ->where('patient_id', $patientId)
            ->where('statut', 'active')
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->exists();
            
        return $directAccess || $delegatedAccess;
    }

    /**
     * Affiche la liste des patients suivis par l'infirmier
     * Inclut les patients assignés directement et ceux via délégation
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function patientsIndex()
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }
        
        // Récupération des patients assignés directement
        $patientsDirects = $infirmier->patients()
            ->with('utilisateur')
            ->get();
        
        // Récupération des patients via les délégations actives
        $delegationsActives = \App\Models\DelegationAcces::query()
            ->where('infirmier_id', $infirmier->utilisateur_id)
            ->where('statut', 'active')
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->with('patient.utilisateur')
            ->get();
            
        // Extraction des patients des délégations
        $patientsDelegues = $delegationsActives->pluck('patient');
        
        // Fusion des deux listes en supprimant les doublons
        $patients = $patientsDirects->merge($patientsDelegues)
            ->unique('id');
        
        return view('infirmier.patients.index', compact('patients'));
    }
    


    /**
     * Affiche les détails d'un patient spécifique
     * Vérifie les droits d'accès via assignation directe ou délégation
     *
     * @param int $id ID du patient à afficher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function patientShow($id)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')
                ->with('error', 'Accès non autorisé');
        }
        
        // Vérification de l'assignation directe
        $hasDirectAccess = $infirmier->patients()
            ->where('patients.id', $id)
            ->exists();
        
        // Si pas d'accès direct, vérification des délégations actives
        if (!$hasDirectAccess) {
            $hasDelegatedAccess = \App\Models\DelegationAcces::query()
                ->where('infirmier_id', $infirmier->utilisateur_id)
                ->where('patient_id', $id)
                ->where('statut', 'active')
                ->where('date_debut', '<=', now())
                ->where('date_fin', '>=', now())
                ->exists();
                
            if (!$hasDelegatedAccess) {
                abort(403, 'Accès non autorisé à ce patient');
            }
        }
        
        // Récupération des informations du patient avec ses relations
        $patient = \App\Models\Patient::with([
                'utilisateur',
                'dossier_medical'
            ])
            ->findOrFail($id);
        
        return view('infirmier.patients.show', compact('patient'));
    }

    /**
     * Liste des traitements en cours
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function traitementsIndex()
    {
        // Récupérer l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }
        
        // Récupérer les patients assignés à cet infirmier
        $patientIds = $infirmier->patients()->pluck('id')->toArray();
        
        // Récupérer tous les traitements actifs des patients
        $traitements = Traitement::with([
                'patient.utilisateur',
                'medecin.utilisateur',
                'medicaments' => function($query) {
                    $query->select(['medicaments.id', 'nom', 'description']);
                }
            ])
            ->whereIn('patient_id', $patientIds)
            ->actifs()
            ->orderBy('date_debut', 'desc')
            ->get();
        
        // Filtrer les traitements d'aujourd'hui
        $traitements_aujourdhui = $traitements->filter(function($traitement) {
            return $traitement->date_debut->isToday() || 
                   ($traitement->dateFin && $traitement->dateFin->isToday());
        });
        
        // Filtrer les traitements en attente de validation
        $traitements_a_valider = $traitements->filter(function($traitement) {
            return $traitement->status === 'EN_ATTENTE';
        });
        
        // Compter les traitements par statut pour les statistiques
        $stats = [
            'total' => $traitements->count(),
            'en_attente' => $traitements->where('status', 'EN_ATTENTE')->count(),
            'en_cours' => $traitements->where('status', 'EN_COURS')->count(),
            'a_venir' => $traitements->filter(fn($t) => $t->date_debut > now())->count(),
            'en_retard' => $traitements->filter(fn($t) => $t->dateFin && $t->dateFin < now() && !in_array($t->status, ['TERMINE', 'ANNULE']))->count(),
        ];
        
        return view('infirmier.traitements.index', [
            'traitements' => $traitements,
            'traitements_aujourdhui' => $traitements_aujourdhui,
            'traitements_a_valider' => $traitements_a_valider,
            'infirmier' => $infirmier,
            'stats' => $stats,
            'types_traitement' => \App\Models\Traitement::TYPES,
            'statuts' => \App\Models\Traitement::STATUTS
        ]);
    }

    /**
     * Liste des alertes patients u00e0 risque
     */
    public function alertesIndex()
    {
        // Ici, vous devrez adapter cette méthode selon votre modèle Alerte
        $alertes = []; // à remplacer par une requête réelle
        return view('infirmier.alertes.index', compact('alertes'));
    }

    /**
     * Liste des notifications
     */
    public function notificationsIndex()
    {
        // Récupérer les notifications non lues de l'utilisateur connecté
        $notifications = auth()->user()->unreadNotifications;
        
        // Récupérer les notifications d'aujourd'hui
        $notifications_aujourdhui = auth()->user()->notifications()
            ->whereDate('created_at', now()->toDateString())
            ->get();
        
        // Récupérer le compte des notifications non lues
        $notifications_non_lues = auth()->user()->unreadNotifications;
        
        return view('infirmier.notifications.index', compact('notifications', 'notifications_aujourdhui', 'notifications_non_lues'));
    }
    
    /**
     * Affiche la liste des observations pour un patient
     *
     * @param int $patientId ID du patient
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    /**
     * Affiche les détails d'une observation spécifique
     *
     * @param int $id ID de l'observation
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showObservation($id)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }
        
        // Récupérer l'observation avec les relations
        $observation = Observation::with(['patient.utilisateur', 'infirmier.utilisateur'])->findOrFail($id);
        
        // Vérifier si l'infirmier a accès à ce patient
        $hasAccess = $this->checkPatientAccess($infirmier, $observation->patient_id);
        if (!$hasAccess) {
            return back()->with('error', 'Accès non autorisé à cette observation');
        }
        
        return view('infirmier.observations.show', [
            'observation' => $observation,
            'patient' => $observation->patient,
            'infirmier' => $infirmier
        ]);
    }
    
    /**
     * Affiche la liste des observations d'un patient
     *
     * @param int $patientId ID du patient
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function patientObservations($patientId)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }
        
        // Vérifier si l'infirmier a accès à ce patient
        $hasAccess = $this->checkPatientAccess($infirmier, $patientId);
        if (!$hasAccess) {
            return back()->with('error', 'Accès non autorisé aux observations de ce patient');
        }
        
        // Récupérer le patient avec ses observations
        $patient = Patient::with(['utilisateur', 'observations' => function($query) {
            $query->orderBy('date_observation', 'desc');
        }])->findOrFail($patientId);
        
        return view('infirmier.observations.index', [
            'patient' => $patient,
            'observations' => $patient->observations,
            'infirmier' => $infirmier
        ]);
    }


    /**
     * Met à jour le statut d'un traitement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $traitementId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTraitementStatus(Request $request, $traitementId)
    {
        // Récupération de l'infirmier connecté
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 401);
        }
        
        // Validation des données
        $validated = $request->validate([
            'statut' => 'required|in:EN_ATTENTE,EN_COURS,PAUSE,TERMINE,ANNULE'
        ]);
        
        // Normaliser le statut pour la base de données
        $validated['status'] = $validated['statut'];
        unset($validated['statut']);
        
        try {
            // Récupération du traitement avec les relations nécessaires
            $traitement = Traitement::with(['patient.utilisateur', 'medecin.utilisateur'])
                ->findOrFail($traitementId);
            
            // Vérifier que l'infirmier a accès au patient de ce traitement
            $hasAccess = $this->checkPatientAccess($infirmier, $traitement->patient_id);
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé à ce traitement'
                ], 403);
            }
            
            // Mise à jour du statut
            $traitement->status = $validated['status'];
            
            // Si le traitement est marqué comme terminé, on met à jour la date de fin
            if ($validated['status'] === 'TERMINE' && !$traitement->dateFin) {
                $traitement->dateFin = now();
            }
            
            $traitement->save();
            
            // Recharger les relations pour s'assurer d'avoir les dernières données
            $traitement->load(['patient.utilisateur', 'medecin.utilisateur']);
            
            return response()->json([
                'success' => true,
                'message' => 'Statut du traitement mis à jour avec succès',
                'traitement' => $traitement
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Traitement non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Marquer une notification comme lue
     */
    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Affiche le formulaire de création d'un nouveau traitement
     *
     * @param int $patientId ID du patient
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createTraitement($patientId)
    {
        $infirmier = $this->getInfirmierFromAuth();
        if (!$infirmier) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        // Vérifier si l'infirmier a accès à ce patient
        if (!$this->checkPatientAccess($infirmier, $patientId)) {
            return redirect()->route('infirmier.patients.index')
                ->with('error', 'Vous n\'avez pas accès à ce patient.');
        }

        $patient = Patient::with('dossierMedical')->findOrFail($patientId);
        $medicaments = Medicament::orderBy('nom')->get();
        
        return view('infirmier.traitements.create', compact('patient', 'medicaments'));
    }

    /**
     * Enregistre un nouveau traitement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $patientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTraitement(Request $request, $patientId)
    {
        \Log::info('Début de la méthode storeTraitement', ['patient_id' => $patientId, 'request_data' => $request->all()]);
        
        try {
            $infirmier = $this->getInfirmierFromAuth();
            if (!$infirmier) {
                \Log::error('Aucun infirmier trouvé pour l\'utilisateur connecté');
                return redirect()->route('login')->with('error', 'Accès non autorisé');
            }
            
            \Log::debug('Infirmier trouvé', ['infirmier_id' => $infirmier->id]);

            // Vérifier si l'infirmier a accès à ce patient
            if (!$this->checkPatientAccess($infirmier, $patientId)) {
                return redirect()->route('infirmier.patients.index')
                    ->with('error', 'Vous n\'avez pas accès à ce patient.');
            }

            // Validation des données
            $validated = $request->validate([
                'type_traitement' => 'required|string|in:' . implode(',', array_keys(\App\Models\Traitement::TYPES)),
                'description' => 'required|string|max:1000',
                'date_debut' => 'required|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'observations' => 'nullable|string|max:2000',
                'medicaments' => 'required_if:type_traitement,MEDICAMENT|array',
                'medicaments.*.id' => 'required_if:type_traitement,MEDICAMENT|exists:medicaments,id',
                'medicaments.*.posologie' => 'required_if:type_traitement,MEDICAMENT|string|max:255',
                'medicaments.*.frequence' => 'required_if:type_traitement,MEDICAMENT|string|max:255',
                'medicaments.*.duree_jours' => 'required_if:type_traitement,MEDICAMENT|integer|min:1',
                'medicaments.*.instructions' => 'nullable|string|max:1000',
            ]);
            
            \Log::info('Validation réussie', $validated);

            // Démarrer une transaction pour s'assurer que tout est bien enregistré
            \DB::beginTransaction();

            try {
                // Récupérer le premier médecin disponible
                $medecin = \App\Models\Medecin::first();
                
                if (!$medecin) {
                    throw new \Exception('Aucun médecin n\'est enregistré dans le système. Veuillez d\'abord créer un médecin.');
                }
                
                \Log::info('Médecin sélectionné', ['medecin_id' => $medecin->id]);
                
                // Créer le traitement
                $traitementData = [
                    'patient_id' => $patientId,
                    'medecin_id' => $medecin->id,
                    'type_traitement' => $validated['type_traitement'],
                    'description' => $validated['description'],
                    'date_debut' => $validated['date_debut'],
                    'date_fin' => $validated['date_fin'] ?? null,
                    'statut' => 'EN_COURS',
                    'observations' => $validated['observations'] ?? null,
                ];
                
                \Log::info('Données du traitement à enregistrer', $traitementData);
                
                $traitement = new \App\Models\Traitement($traitementData);
                $traitement->save();
                \Log::info('Traitement enregistré avec succès', ['traitement_id' => $traitement->id]);

                // Si c'est un traitement médicamenteux, attacher les médicaments
                if ($validated['type_traitement'] === 'MEDICAMENT' && !empty($validated['medicaments'])) {
                    foreach ($validated['medicaments'] as $medicament) {
                        $traitement->medicaments()->attach($medicament['id'], [
                            'posologie' => $medicament['posologie'],
                            'frequence' => $medicament['frequence'],
                            'duree_jours' => $medicament['duree_jours'],
                            'instructions' => $medicament['instructions'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Enregistrer une activité
                if (class_exists('Spatie\Activitylog\Traits\LogsActivity')) {
                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($traitement)
                        ->withProperties(['patient_id' => $patientId])
                        ->log('Nouveau traitement créé par l\'infirmier');
                }

                \DB::commit();

                return redirect()->route('infirmier.patients.show', $patientId)
                    ->with('success', 'Le traitement a été enregistré avec succès.');

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Erreur lors de la création du traitement: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
                return back()->withInput()->with('error', 'Erreur lors de l\'enregistrement du traitement: ' . $e->getMessage());
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Erreur inattendue: ' . $e->getMessage() . '\n' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Une erreur inattendue est survenue. Veuillez réessayer.');
        }
    }
    
    /**
     * Met à jour le mot de passe de l'utilisateur
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $infirmier = $this->getInfirmierFromAuth();
            if (!$infirmier) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }
            
            $user = $infirmier->utilisateur;
            
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            
            $user->update([
                'mot_de_passe' => Hash::make($validated['password']),
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Votre mot de passe a été mis à jour avec succès.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du mot de passe: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Une erreur est survenue lors de la mise à jour du mot de passe.'
            ], 500);
        }
    }
}

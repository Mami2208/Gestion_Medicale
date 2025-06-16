<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\HistoriqueMedical;
use App\Models\Medecin;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DossierController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Affiche la liste des dossiers médicaux
     */
    public function index(Request $request)
    {
        // Récupérer l'ID du médecin associé à l'utilisateur connecté
        $medecinId = Auth::user()->medecin->id;
        
        $query = Dossier::with(['patient.utilisateur', 'consultations', 'examens', 'prescriptions'])
            ->where('medecin_id', $medecinId);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_dossier', 'like', "%{$search}%")
                  ->orWhereHas('patient.utilisateur', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $dossiers = $query->latest()->paginate(10);
        $patients = Patient::with('utilisateur')
            ->join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->orderBy('utilisateurs.nom')
            ->select('patients.*')
            ->get();

        return view('medecin.dossiers.index', compact('dossiers', 'patients'));
    }

    /**
     * Affiche le formulaire de création d'un dossier
     */
    public function create()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.dossiers.create', compact('patients'));
    }

    /**
     * Enregistre un nouveau dossier
     */
    public function store(Request $request)
    {
        // Forcer la réponse JSON pour les requêtes AJAX
        $isAjax = $request->ajax() || $request->wantsJson();
        if ($isAjax) {
            $request->headers->set('Accept', 'application/json');
        }
        
        // Activer le mode débogage pour voir toutes les erreurs
        \DB::enableQueryLog();
        
        \Log::info('Début de la méthode store', [
            'method' => $request->method(),
            'ajax' => $isAjax,
            'contentType' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->ip(),
            'data_keys' => array_keys($request->except(['password', 'password_confirmation']))
        ]);
        
        try {
            // Vérifier si l'utilisateur est authentifié
            if (!Auth::check()) {
                $errorMessage = 'Veuillez vous connecter pour créer un dossier.';
                \Log::warning('Tentative de création sans authentification');
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'redirect' => route('login')
                    ], 401);
                }
                return redirect()->route('login')->with('error', $errorMessage);
            }
            
            // Récupérer l'utilisateur connecté
            $user = Auth::user();
            
            // Récupérer le rôle de l'utilisateur
            $userRole = $user->role;
            
            \Log::info('Début de la création d\'un nouveau patient et dossier', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role' => $userRole,
                'request_data' => array_merge(
                    $request->except(['password', 'password_confirmation']),
                    ['has_password' => $request->has('password')]
                )
            ]);

            // Vérifier si l'utilisateur est un médecin
            $medecin = Medecin::where('utilisateur_id', $user->id)->first();
            if (!$medecin) {
                $errorMessage = 'Seul un médecin peut créer des dossiers médicaux.';
                \Log::warning('Tentative de création de dossier par un non-médecin', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'redirect' => route('home')
                    ], 403);
                }
                return redirect()->route('home')->with('error', $errorMessage);
            }

            // Validation des données du formulaire
            \Log::info('Début de la validation des données', ['data' => $request->all()]);
            
            // Validation des données utilisateur
            $userRules = [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:utilisateurs,email',
                'password' => 'required|string|min:8|confirmed',
                'telephone' => 'required|string|max:20',
                'date_naissance' => 'required|date',
                'genre' => 'required|in:M,F,A',
                'adresse' => 'nullable|string',
                'groupe_sanguin' => 'nullable|string|max:5',
            ];
            
            // Validation des données du dossier médical
            $dossierRules = [
                'statut' => 'required|in:ACTIF,ARCHIVE,FERME',
                'taille' => 'nullable|numeric|min:0',
                'poids' => 'nullable|numeric|min:0',
                'antecedents_medicaux' => 'nullable|string',
                'allergies' => 'nullable|string',
                'observations' => 'nullable|string',
                'notes' => 'nullable|string'
            ];
            
            // Valider d'abord les données utilisateur
            $userValidator = \Validator::make($request->all(), $userRules);
            if ($userValidator->fails()) {
                $errors = $userValidator->errors();
                \Log::error('Échec de la validation utilisateur', [
                    'errors' => $errors->toArray(),
                    'input' => $request->except('password', 'password_confirmation')
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur de validation des données utilisateur',
                        'errors' => $errors,
                        'input' => $request->except('password', 'password_confirmation')
                    ], 422);
                }
                return back()->withErrors($userValidator)->withInput();
            }
            
            // Ensuite valider les données du dossier
            $dossierValidator = \Validator::make($request->all(), $dossierRules);
            if ($dossierValidator->fails()) {
                $errors = $dossierValidator->errors();
                \Log::error('Échec de la validation du dossier', [
                    'errors' => $errors->toArray(),
                    'input' => $request->only(array_keys($dossierRules))
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur de validation des données médicales',
                        'errors' => $errors,
                        'input' => $request->only(array_keys($dossierRules))
                    ], 422);
                }
                return back()->withErrors($dossierValidator)->withInput();
            }
            
            // Si tout est valide, fusionner les données
            $validated = array_merge($userValidator->validated(), $dossierValidator->validated());
            \Log::info('Données validées avec succès', ['validated' => $validated]);
            
            // Vérifier si l'utilisateur est toujours authentifié après la validation
            if (!Auth::check()) {
                $errorMessage = 'La session a expiré. Veuillez vous reconnecter.';
                \Log::error('Session expirée pendant la validation', [
                    'user_was_authenticated' => true,
                    'validated_data' => $validated
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'redirect' => route('login')
                    ], 401);
                }
                return redirect()->route('login')->with('error', $errorMessage);
            }

            // Validation des données médicales séparément
            try {
                $validatedMedical = $request->validate([
                    'statut' => 'required|in:ACTIF,ARCHIVE,FERME',
                    'groupe_sanguin' => 'nullable|string|max:5',
                    'taille' => 'nullable|numeric|min:0',
                    'poids' => 'nullable|numeric|min:0',
                    'antecedents_medicaux' => 'nullable|string',
                    'allergies' => 'nullable|string',
                    'observations' => 'nullable|string',
                    'notes' => 'nullable|string'
                ]);
                
                // Fusionner les tableaux validés
                $validated = array_merge($validated, $validatedMedical);
                \Log::debug('Données validées avec succès', ['fields' => array_keys($validated)]);
                
            } catch (\Illuminate\Validation\ValidationException $e) {
                $errors = $e->validator->errors();
                \Log::error('Erreur de validation des données médicales', [
                    'errors' => $errors->toArray(),
                    'input' => $request->only(['statut', 'groupe_sanguin', 'taille', 'poids'])
                ]);
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur de validation des données médicales',
                        'errors' => $errors
                    ], 422);
                }
                throw $e;
            }

            // Démarrer la transaction
            \DB::beginTransaction();
            \Log::info('Transaction démarrée');

            try {
                // Créer l'utilisateur avec les données validées
                $utilisateurData = [
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'mot_de_passe' => bcrypt($validated['password']), // Utiliser le nom de colonne correct
                    'telephone' => $validated['telephone'],
                    'date_naissance' => $validated['date_naissance'],
                    'genre' => $validated['genre'],
                    'adresse' => $validated['adresse'] ?? null,
                    'role' => 'PATIENT', // Définir le rôle par défaut
                    'statut' => 'ACTIF' // Définir le statut par défaut
                ];
                
                \Log::debug('Création de l\'utilisateur', ['data' => array_merge($utilisateurData, ['mot_de_passe' => '*****'])]);
                
                try {
                    $utilisateur = new \App\Models\Utilisateur($utilisateurData);
                    $utilisateur->save();
                    \Log::info('Utilisateur créé avec succès', [
                        'utilisateur_id' => $utilisateur->id,
                        'email' => $utilisateur->email
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la création de l\'utilisateur', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'data' => array_merge($utilisateurData, ['password' => '*****'])
                    ]);
                    throw new \Exception('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage(), 0, $e);
                }

                // Créer le patient associé
                $patientData = [
                    'utilisateur_id' => $utilisateur->id,
                    'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
                    'numero_securite_sociale' => $validated['numero_securite_sociale'] ?? null,
                    'numeroPatient' => 'PAT-' . strtoupper(uniqid()), // Générer un numéro de patient unique
                ];
                
                \Log::debug('Création du patient', ['data' => $patientData]);
                
                try {
                    $patient = new \App\Models\Patient($patientData);
                    $patient->save();
                    \Log::info('Patient créé avec succès', [
                        'patient_id' => $patient->id,
                        'utilisateur_id' => $utilisateur->id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la création du patient', [
                        'utilisateur_id' => $utilisateur->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception('Erreur lors de la création du patient: ' . $e->getMessage(), 0, $e);
                }

                // Attribuer le rôle PATIENT à l'utilisateur
                try {
                    $utilisateur->role = 'PATIENT';
                    $utilisateur->save();
                    \Log::info('Rôle PATIENT attribué avec succès', [
                        'utilisateur_id' => $utilisateur->id,
                        'role' => 'PATIENT'
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'attribution du rôle PATIENT', [
                        'utilisateur_id' => $utilisateur->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }

                try {
                    // Récupérer l'ID du médecin connecté
                    $medecinId = null;
                    $medecin = Medecin::where('utilisateur_id', auth()->id())->first();
                    if ($medecin) {
                        $medecinId = $medecin->id;
                    }

                    // Créer le dossier médical avec les données validées
                    $dossierData = [
                        'patient_id' => $patient->id,
                        'medecin_id' => $medecinId, // Peut être null
                        'numero_dossier' => Dossier::genererNumeroDossier(),
                        'antecedents_medicaux' => !empty($validated['antecedents_medicaux']) ? 
                            json_encode(['antecedents' => $validated['antecedents_medicaux']]) : null,
                        'allergies' => !empty($validated['allergies']) ? 
                            json_encode(['allergies' => $validated['allergies']]) : null,
                        'observations' => $validated['observations'] ?? null,
                        'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
                        'taille' => $validated['taille'] ?? null,
                        'poids' => $validated['poids'] ?? null,
                        'statut' => 'ACTIF',
                        'date_creation' => now()
                    ];
                    
                    \Log::debug('Données du dossier médical à créer', [
                        'dossier_data' => $dossierData,
                        'medecin_trouve' => $medecin ? true : false,
                        'medecin_id' => $medecinId
                    ]);
                    
                    \Log::debug('Création du dossier médical', [
                        'patient_id' => $patient->id,
                        'medecin_id' => auth()->id(),
                        'data' => array_merge($dossierData, [
                            'antecedents_medicaux' => isset($dossierData['antecedents_medicaux']) ? '[...]' : null,
                            'allergies' => isset($dossierData['allergies']) ? '[...]' : null,
                            'observations' => isset($dossierData['observations']) ? '[...]' : null,
                        ])
                    ]);
                    
                    // Utiliser le modèle DossierMedical au lieu de Dossier
                    $dossier = \App\Models\DossierMedical::create($dossierData);
                    \Log::info('Dossier médical créé avec succès', [
                        'dossier_id' => $dossier->id,
                        'patient_id' => $patient->id,
                        'medecin_id' => auth()->id(),
                        'numero_dossier' => $dossier->numero_dossier
                    ]);
                    
                    // Valider que tout est cohérent avant de committer
                    if (!$dossier->exists) {
                        throw new \Exception('Le dossier médical n\'a pas pu être créé');
                    }
                    
                    // Tout s'est bien passé, on peut committer la transaction
                    \DB::commit();
                    \Log::info('Transaction commitée avec succès', [
                        'dossier_id' => $dossier->id,
                        'patient_id' => $patient->id,
                        'utilisateur_id' => $utilisateur->id
                    ]);
                    
                    // Préparer la réponse de succès
                    $successMessage = 'Patient et dossier médical créés avec succès.';
                    $redirectUrl = route('medecin.dossiers.show', $dossier->id);
                    
                    // Envoyer un email de bienvenue avec les identifiants (optionnel)
                    try {
                        // Mail::to($utilisateur->email)->send(new WelcomeNewUser($utilisateur, $validated['password']));
                        \Log::debug('Email de bienvenue envoyé avec succès', [
                            'email' => $utilisateur->email
                        ]);
                    } catch (\Exception $e) {
                        // Ne pas échouer la requête si l'envoi d'email échoue
                        \Log::error('Erreur lors de l\'envoi de l\'email de bienvenue', [
                            'email' => $utilisateur->email,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    if ($isAjax) {
                        return response()->json([
                            'success' => true,
                            'message' => $successMessage,
                            'redirect' => $redirectUrl,
                            'dossier_id' => $dossier->id
                        ]);
                    }
                    
                    return redirect($redirectUrl)->with('success', $successMessage);
                    
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la création du dossier médical', [
                        'patient_id' => $patient->id,
                        'medecin_id' => $medecin->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'dossier_data' => isset($dossierData) ? $dossierData : null
                    ]);
                    throw new \Exception('Erreur lors de la création du dossier médical: ' . $e->getMessage(), 0, $e);
                }

            } catch (\Exception $e) {
                // Rollback de la transaction en cas d'erreur
                try {
                    if (\DB::transactionLevel() > 0) {
                        \DB::rollBack();
                        \Log::warning('Rollback de la transaction effectué');
                    }
                } catch (\Exception $rollbackException) {
                    \Log::error('Erreur lors du rollback de la transaction', [
                        'original_error' => $e->getMessage(),
                        'rollback_error' => $rollbackException->getMessage()
                    ]);
                }
                
                // Journalisation détaillée de l'erreur
                $errorContext = [
                    'error' => [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],
                    'request' => [
                        'method' => $request->method(),
                        'url' => $request->fullUrl(),
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'input' => $request->except(['password', 'password_confirmation'])
                    ],
                    'user' => Auth::check() ? [
                        'id' => Auth::id(),
                        'email' => Auth::user()->email,
                        'role' => Auth::user()->role
                    ] : 'non authentifié'
                ];
                
                \Log::error('Erreur lors de la création du patient et du dossier', $errorContext);
                
                // Nettoyage : supprimer l'utilisateur créé si nécessaire
                if (isset($utilisateur) && $utilisateur->exists) {
                    try {
                        $userId = $utilisateur->id;
                        $userEmail = $utilisateur->email;
                        
                        // D'abord supprimer les relations pour éviter les contraintes de clé étrangère
                        if (isset($patient) && $patient->exists) {
                            $patient->forceDelete();
                        }
                        
                        // Puis supprimer l'utilisateur
                        $utilisateur->forceDelete();
                        
                        \Log::warning('Utilisateur supprimé suite à l\'échec de la création du dossier', [
                            'user_id' => $userId,
                            'email' => $userEmail
                        ]);
                    } catch (\Exception $deleteException) {
                        \Log::error('Erreur lors du nettoyage après échec', [
                            'original_error' => $e->getMessage(),
                            'cleanup_error' => $deleteException->getMessage(),
                            'user_id' => isset($utilisateur) ? $utilisateur->id : 'non défini'
                        ]);
                    }
                }
                
                // Préparer le message d'erreur
                $errorMessage = 'Une erreur est survenue lors de la création du patient et du dossier';
                $debugMessage = config('app.debug') ? ': ' . $e->getMessage() : '';
                
                // Répondre en fonction du type de requête
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage . $debugMessage,
                        'error' => config('app.debug') ? [
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTrace()
                        ] : null,
                        'request_id' => $request->header('X-Request-ID')
                    ], 500);
                }
                
                // Pour les requêtes normales, rediriger avec un message d'erreur
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'error' => $errorMessage . $debugMessage
                    ])
                    ->with('error_details', config('app.debug') ? $e->getMessage() : null);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $validator = $e->validator;
            $errors = $validator->errors();
            
            // Journalisation détaillée des erreurs de validation
            \Log::warning('Erreur de validation dans le formulaire', [
                'errors' => $errors->toArray(),
                'input' => $request->except(['password', 'password_confirmation']),
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Des erreurs de validation sont survenues',
                    'errors' => $errors,
                    'error_summary' => $errors->first(),
                    'input' => $request->except(['password', 'password_confirmation'])
                ], 422);
            }
            
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.');
                
        } catch (\Exception $e) {
            // Journalisation détaillée de l'erreur inattendue
            \Log::error('Erreur inattendue dans la méthode store', [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ],
                'request' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'input' => $request->except(['password', 'password_confirmation']),
                    'headers' => $request->headers->all()
                ],
                'user' => Auth::check() ? [
                    'id' => Auth::id(),
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ] : 'non authentifié'
            ]);
            
            // Préparer le message d'erreur adapté au contexte
            $errorMessage = 'Une erreur inattendue est survenue';
            $debugMessage = config('app.debug') ? ': ' . $e->getMessage() : '';
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage . $debugMessage,
                    'error' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : null,
                    'request_id' => $request->header('X-Request-ID')
                ], 500);
            }
            
            // Pour les requêtes normales, rediriger avec un message d'erreur
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage . $debugMessage)
                ->with('show_contact_support', true);
        }
    }

    /**
     * Affiche les détails d'un dossier
     */
    public function show(Dossier $dossier)
    {
        $this->authorize('view', $dossier);
        
        $dossier->load([
            'patient.utilisateur',
            'medecin.utilisateur',
            'consultations', 
            'examens', 
            'prescriptions',
            'historiquesMedicaux'
        ]);
        
        return view('medecin.dossiers.show', compact('dossier'));
    }

    /**
     * Affiche le formulaire d'édition d'un dossier
     */
    public function edit(Dossier $dossier)
    {
        $this->authorize('update', $dossier);
        
        return view('medecin.dossiers.edit', compact('dossier'));
    }

    /**
     * Met à jour un dossier
     */
    public function update(Request $request, Dossier $dossier)
    {
        $this->authorize('update', $dossier);

        $validated = $request->validate([
            'statut' => 'required|in:ACTIF,ARCHIVE,FERME',
            'groupe_sanguin' => 'nullable|string|max:5',
            'taille' => 'nullable|numeric|min:0',
            'poids' => 'nullable|numeric|min:0',
            'antecedents_medicaux' => 'nullable|string',
            'allergies' => 'nullable|string',
            'observations' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $dossier->update($validated);

        return redirect()
            ->route('medecin.dossiers.show', $dossier)
            ->with('success', 'Dossier médical mis à jour avec succès.');
    }

    /**
     * Supprime un dossier
     */
    public function destroy(Dossier $dossier)
    {
        $this->authorize('delete', $dossier);

        $dossier->delete();

        return redirect()
            ->route('medecin.dossiers.index')
            ->with('success', 'Dossier médical supprimé avec succès.');
    }
} 
<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DelegationAcces;
use App\Models\Patient;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DelegationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medecinId = auth()->id();
        \Illuminate\Support\Facades\Log::info('ID du médecin connecté: ' . $medecinId);
        
        // Vérifier que l'utilisateur est bien un médecin
        $medecin = \App\Models\Medecin::where('utilisateur_id', $medecinId)->first();
        
        if (!$medecin) {
            \Illuminate\Support\Facades\Log::error('L\'utilisateur connecté n\'est pas un médecin', ['user_id' => $medecinId]);
            return redirect()->route('medecin.dashboard')->with('error', 'Accès refusé. Vous devez être un médecin pour accéder à cette page.');
        }
        
        // Récupérer les délégations avec les relations
        $query = DelegationAcces::where('medecin_id', $medecin->id)
            ->with([
                'infirmier',
                'patient.utilisateur'
            ])
            ->orderBy('created_at', 'desc');
            
        // Log pour le débogage
        \Illuminate\Support\Facades\Log::info('Requête SQL: ' . $query->toSql());
        \Illuminate\Support\Facades\Log::info('Paramètres: ', $query->getBindings());
        
        // Exécuter la requête de pagination
        $delegations = $query->paginate(10);
        \Illuminate\Support\Facades\Log::info('Nombre de délégations trouvées: ' . $delegations->total());
        
        // Afficher les données brutes pour le débogage
        $rawDelegations = DelegationAcces::where('medecin_id', $medecin->id)->get();
        \Illuminate\Support\Facades\Log::info('Délégations brutes: ' . json_encode($rawDelegations->toArray(), JSON_PRETTY_PRINT));
        
        // Vérifier les relations
        foreach ($rawDelegations as $delegation) {
            \Illuminate\Support\Facades\Log::info('Délégation: ' . $delegation->id);
            \Illuminate\Support\Facades\Log::info('Infirmier: ' . ($delegation->infirmier ? 'Oui' : 'Non'));
            \Illuminate\Support\Facades\Log::info('Patient: ' . ($delegation->patient ? 'Oui' : 'Non'));
            if ($delegation->patient) {
                \Illuminate\Support\Facades\Log::info('Utilisateur du patient: ' . ($delegation->patient->utilisateur ? 'Oui' : 'Non'));
            }
        }
            
        return view('medecin.delegations.index', compact('delegations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer tous les patients avec leurs informations utilisateur
        $patients = Patient::join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->select('patients.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email')
            ->orderBy('utilisateurs.nom')
            ->orderBy('utilisateurs.prenom')
            ->get();
        
        \Illuminate\Support\Facades\Log::info('Patients récupérés avec utilisateurs: ' . $patients->count());
            
        // Récupérer tous les infirmiers avec leurs informations utilisateurs
        $infirmiers = \App\Models\Infirmier::join('utilisateurs', 'infirmiers.utilisateur_id', '=', 'utilisateurs.id')
            ->select('infirmiers.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email', 'utilisateurs.role')
            ->orderBy('utilisateurs.nom')
            ->orderBy('utilisateurs.prenom')
            ->get();
            
        \Illuminate\Support\Facades\Log::info('Infirmiers récupérés: ' . $infirmiers->count());
            
        return view('medecin.delegations.create', compact('patients', 'infirmiers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si c'est une requête AJAX pour éviter les soumissions multiples
        if ($request->ajax()) {
            return response()->json(['error' => 'Soumission non autorisée'], 403);
        }

        // Valider les données du formulaire
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'infirmier_id' => 'required|exists:infirmiers,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'raison' => 'nullable|string|max:500'
        ]);
        
        // Récupérer le médecin pour l'utilisateur connecté
        $medecinConnecte = \App\Models\Medecin::where('utilisateur_id', auth()->id())->first();
        
        if (!$medecinConnecte) {
            \Illuminate\Support\Facades\Log::error('Utilisateur connecté (ID: ' . auth()->id() . ') n\'a pas d\'enregistrement dans la table des médecins');
            return back()->with('error', 'Erreur: vous n\'avez pas de profil médecin');
        }
        
        // Récupérer le patient
        $patient = Patient::with('utilisateur')->findOrFail($request->patient_id);
        
        // Vérifier que l'infirmier existe et récupérer son utilisateur
        $infirmier = \App\Models\Infirmier::with('utilisateur')->findOrFail($request->infirmier_id);
        
        if (!$infirmier->utilisateur) {
            \Illuminate\Support\Facades\Log::error('Aucun utilisateur associé à l\'infirmier ID: ' . $infirmier->id);
            return back()->with('error', 'Erreur: Aucun utilisateur associé à cet infirmier');
        }
        
        $infirmierUserId = $infirmier->utilisateur->id;
        
        // Vérifier si une délégation identique existe déjà
        $existingDelegation = DelegationAcces::where([
            'medecin_id' => $medecinConnecte->id,
            'infirmier_id' => $infirmierUserId,
            'patient_id' => $patient->id,
            'statut' => 'active'
        ])->where('date_fin', '>=', now())->first();
        
        if ($existingDelegation) {
            return back()->with('warning', 'Une délégation active existe déjà pour ce patient et cet infirmier.');
        }
        
        try {
            // Utiliser une transaction pour s'assurer que tout se passe bien
            \DB::beginTransaction();
            
            // Créer la délégation
            $delegation = DelegationAcces::create([
                'medecin_id' => $medecinConnecte->id,
                'infirmier_id' => $infirmierUserId,
                'patient_id' => $patient->id,
                'date_debut' => Carbon::parse($request->date_debut),
                'date_fin' => Carbon::parse($request->date_fin),
                'raison' => $request->raison,
                'statut' => 'active'
            ]);
            
            // Enregistrer l'action dans les logs
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'type' => 'delegation',
                'description' => "Délégation d'accès au dossier du patient #{$patient->id} à l'infirmier {$infirmier->utilisateur->prenom} {$infirmier->utilisateur->nom}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Créer une notification pour l'infirmier
            $notification = new \App\Models\Notification([
                'user_id' => $infirmierUserId,
                'type' => 'delegation_created',
                'title' => 'Nouvelle délégation de patient',
                'message' => "Le Dr " . auth()->user()->prenom . " " . auth()->user()->nom . 
                            " vous a délégué l'accès au dossier de " . $patient->utilisateur->prenom . " " . $patient->utilisateur->nom . 
                            " jusqu'au " . Carbon::parse($request->date_fin)->format('d/m/Y') . 
                            ($request->raison ? "\nRaison : " . $request->raison : ''),
                'is_read' => false,
                'data' => json_encode([
                    'delegation_id' => $delegation->id,
                    'patient_id' => $patient->id,
                    'patient_name' => $patient->utilisateur->prenom . ' ' . $patient->utilisateur->nom,
                    'medecin_name' => auth()->user()->prenom . ' ' . auth()->user()->nom,
                    'date_fin' => $request->date_fin,
                    'raison' => $request->raison
                ])
            ]);
            $notification->save();
            
            // Valider la transaction
            \DB::commit();
            
            // Rediriger avec un message de succès
            return redirect()->route('medecin.delegations.index')
                ->with('success', 'Délégation créée avec succès.');
                
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Erreur lors de la création de la délégation: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Une erreur est survenue lors de la création de la délégation. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $delegation = DelegationAcces::where('medecin_id', auth()->id())
            ->with(['infirmier', 'patient'])
            ->findOrFail($id);
            
        return view('medecin.delegations.show', compact('delegation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $delegation = DelegationAcces::where('medecin_id', auth()->id())
            ->findOrFail($id);
            
        // Ne pas permettre l'édition des délégations terminées ou annulées
        if ($delegation->statut !== 'active') {
            return redirect()->route('medecin.delegations.index')
                ->with('error', 'Vous ne pouvez pas modifier une délégation ' . $delegation->statut);
        }
        
        // Récupérer tous les patients avec leurs informations utilisateur
        $patients = Patient::join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->select('patients.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email')
            ->orderBy('utilisateurs.nom')
            ->orderBy('utilisateurs.prenom')
            ->get();
        
        \Illuminate\Support\Facades\Log::info('Page edit: Patients récupérés avec utilisateurs: ' . $patients->count());
            
        // Récupérer tous les infirmiers avec leurs informations utilisateurs
        $infirmiers = \App\Models\Infirmier::join('utilisateurs', 'infirmiers.utilisateur_id', '=', 'utilisateurs.id')
            ->select('infirmiers.*', 'utilisateurs.nom', 'utilisateurs.prenom', 'utilisateurs.email', 'utilisateurs.role')
            ->orderBy('utilisateurs.nom')
            ->orderBy('utilisateurs.prenom')
            ->get();
            
        \Illuminate\Support\Facades\Log::info('Edit: Infirmiers récupérés: ' . $infirmiers->count());
            
        return view('medecin.delegations.edit', compact('delegation', 'patients', 'infirmiers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $delegation = DelegationAcces::where('medecin_id', auth()->id())
            ->findOrFail($id);
            
        // Ne pas permettre l'édition des délégations terminées ou annulées
        if ($delegation->statut !== 'active') {
            return redirect()->route('medecin.delegations.index')
                ->with('error', 'Vous ne pouvez pas modifier une délégation ' . $delegation->statut);
        }
        
        $request->validate([
            'date_fin' => 'required|date|after:now',
            'raison' => 'nullable|string|max:500',
            'statut' => 'required|in:active,terminee,annulee'
        ]);
        
        $delegation->update([
            'date_fin' => Carbon::parse($request->date_fin),
            'raison' => $request->raison,
            'statut' => $request->statut
        ]);
        
        // Enregistrer l'action dans les logs
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'type' => 'delegation',
            'description' => "Mise à jour de la délégation d'accès #$id",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('medecin.delegations.index')
            ->with('success', 'Délégation mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delegation = DelegationAcces::where('medecin_id', auth()->id())
            ->findOrFail($id);
            
        // Marquer comme annulée plutôt que supprimer pour garder une trace
        $delegation->update([
            'statut' => 'annulee'
        ]);
        
        // Enregistrer l'action dans les logs
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'cancel',
            'type' => 'delegation',
            'description' => "Annulation de la délégation d'accès #$id",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        return redirect()->route('medecin.delegations.index')
            ->with('success', 'Délégation annulée avec succès');
    }
}

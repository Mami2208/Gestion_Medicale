<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Prescription;


class PatientController extends Controller
{
    public function dashboard()
    {
        // Récupérer le patient connecté
        $user = auth()->user();
        $patient = $user->patient;
        
        // Récupérer les rendez-vous à venir
        $rendezVous = $patient->rendezVous()
            ->where('date_rendez_vous', '>=', now())
            ->orderBy('date_rendez_vous', 'asc')
            ->take(5)
            ->get();
        
        // Récupérer les dossiers médicaux
        $dossiersMedicaux = $patient->dossiers_medicaux()
            ->latest()
            ->take(5)
            ->get();
            
        // Récupérer le dossier médical le plus récent pour afficher les informations détaillées
        $dossierMedical = $patient->dossiers_medicaux()
            ->latest()
            ->first();
        
        // Récupérer les traitements du patient
        $traitements = $patient->traitements()
            ->latest()
            ->take(5)
            ->get();
        
        return view('patient.dashboard', compact('patient', 'rendezVous', 'dossiersMedicaux', 'traitements', 'dossierMedical'));
    }

    public function profile()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        return view('patient.profile', compact('patient'));
    }
    
    /**
     * Afficher le dossier médical du patient
     */
    public function dossierMedical()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        // Charger les relations nécessaires
        $patient->load([
            'utilisateur', 
            'dossierMedical.medecin.utilisateur',
            'traitements' => function($query) {
                $query->where('statut', 'en_cours')
                      ->orWhere('date_fin', '>=' , now())
                      ->orWhereNull('date_fin')
                      ->orderBy('date_debut', 'desc');
            },
            'dossierMedical.historiques' => function($query) {
                $query->where('type', 'diagnostic')
                      ->orderBy('date', 'desc')
                      ->with('medecin.utilisateur');
            }
        ]);
        
        // Si le patient n'a pas encore de dossier médical, on en crée un vide
        if (!$patient->dossierMedical) {
            $patient->dossierMedical = new \App\Models\DossierMedical([
                'patient_id' => $patient->id,
                'date_creation' => now(),
                'statut' => 'actif'
            ]);
        }
        
        return view('patient.dossier.index', compact('patient'));
    }
    
    /**
     * Afficher la liste des rendez-vous du patient
     */
    public function appointments()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        // Récupérer les rendez-vous à venir
        $rendezVous = $patient->rendezVous()
            ->with('medecin.utilisateur')
            ->where('date_rendez_vous', '>=', now()->format('Y-m-d'))
            ->orderBy('date_rendez_vous', 'asc')
            ->get();
            
        // Récupérer la liste des médecins actifs
        $medecins = \App\Models\Medecin::with('utilisateur')
            ->whereHas('utilisateur', function($query) {
                $query->where('statut', 'ACTIF');
            })
            ->get();
        
        return view('patient.appointments', compact('rendezVous', 'medecins'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|string|in:M,F,Autre',
            'groupe_sanguin' => 'nullable|string|max:10',
        ]);
        
        // Mise à jour des données utilisateur
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->date_naissance = $request->date_naissance; 
        $user->sexe = $request->sexe; 
        $user->save();
        
        // Mise à jour des données patient
        $patient->adresse = $request->adresse;
        if (Schema::hasColumn('patients', 'groupe_sanguin')) {
            $patient->groupe_sanguin = $request->groupe_sanguin;
        }
        $patient->save();
        
        return redirect()->route('patient.profile')->with('success', 'Profil mis à jour avec succès');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }
        
        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('patient.profile')->with('success', 'Mot de passe modifié avec succès');
    }

    public function medicalRecords()
    {
        return view('patient.medical_records');
    }
    
    /**
     * Annuler un rendez-vous
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelAppointment(Request $request, $id)
    {
        try {
            $rendezVous = \App\Models\RendezVous::where('id', $id)
                ->where('patient_id', auth()->user()->patient->id)
                ->firstOrFail();
                
            // Vérifier si le rendez-vous peut être annulé
            if ($rendezVous->statut === 'ANNULE') {
                return redirect()->back()->with('error', 'Ce rendez-vous a déjà été annulé.');
            }
            
            if ($rendezVous->statut === 'TERMINE') {
                return redirect()->back()->with('error', 'Impossible d\'annuler un rendez-vous déjà terminé.');
            }
            
            // Mettre à jour le statut du rendez-vous
            $rendezVous->update([
                'statut' => 'ANNULE',
                'raison_annulation' => $request->input('raison_annulation', 'Annulé par le patient')
            ]);
            
            // Créer une notification pour le médecin
            if ($rendezVous->medecin && $rendezVous->medecin->utilisateur) {
                $dateFormatee = $rendezVous->date_rendez_vous instanceof \DateTime 
                    ? $rendezVous->date_rendez_vous->format('d/m/Y') 
                    : date('d/m/Y', strtotime($rendezVous->date_rendez_vous));
                
                \App\Models\Notification::create([
                    'title' => 'Rendez-vous annulé',
                    'message' => "Le rendez-vous du {$dateFormatee} à {$rendezVous->heure_debut} avec le patient " . 
                                auth()->user()->prenom . ' ' . auth()->user()->nom . 
                                " a été annulé. Raison: " . ($request->input('raison_annulation') ?? 'Non spécifiée'),
                    'dateEnvoi' => now(),
                    'typeLecture' => 0, // 0 = non lu
                    'medecin_id' => $rendezVous->medecin_id,
                    'user_id' => $rendezVous->medecin->utilisateur->id,
                ]);
            }
            
            return redirect()->route('patient.appointments')
                ->with('success', 'Le rendez-vous a été annulé avec succès.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'annulation du rendez-vous', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'rendez_vous_id' => $id
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'annulation du rendez-vous. Veuillez réessayer.');
        }
    }
    
    /**
     * Enregistre un nouveau rendez-vous
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Enregistre un nouveau rendez-vous
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAppointment(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'medecin_id' => 'required|exists:medecins,id',
            'date_rendez_vous' => 'required|date|after_or_equal:today',
            'creneau' => 'required|string|in:matin,apres-midi',
            'motif' => 'required|string|max:500',
        ]);
        
        try {
            // Récupérer l'utilisateur connecté et son profil patient
            $user = auth()->user();
            $patient = $user->patient;
            
            if (!$patient) {
                return redirect()->back()
                    ->with('error', 'Aucun profil patient trouvé pour votre compte. Veuillez contacter l\'administration.');
            }
            
            // Vérifier si le médecin est disponible
            $medecin = \App\Models\Medecin::with('utilisateur')->find($validated['medecin_id']);
            if (!$medecin || !$medecin->utilisateur || strtoupper($medecin->utilisateur->statut) !== 'ACTIF') {
                return redirect()->back()
                    ->with('error', 'Le médecin sélectionné n\'est pas disponible. Veuillez en choisir un autre.');
            }
            
            // Définir les heures de début et de fin en fonction du créneau
            $heure_debut = $validated['creneau'] === 'matin' ? '08:00:00' : '14:00:00';
            $heure_fin = $validated['creneau'] === 'matin' ? '12:00:00' : '18:00:00';
            
            // Créer le rendez-vous
            $rendezVous = new \App\Models\RendezVous([
                'patient_id' => $patient->id,
                'medecin_id' => $validated['medecin_id'],
                'date_rendez_vous' => $validated['date_rendez_vous'],
                'heure_debut' => $heure_debut,
                'heure_fin' => $heure_fin,
                'motif' => $validated['motif'],
                'type_rendez_vous' => 'CONSULTATION',
                'statut' => 'PLANIFIE',
            ]);
            
            $rendezVous->save();
            
            // Créer une notification pour le médecin
            if ($medecin->utilisateur) {
                $dateFormatee = \Carbon\Carbon::parse($validated['date_rendez_vous'])->format('d/m/Y');
                
                \App\Models\Notification::create([
                    'title' => 'Nouveau rendez-vous planifié',
                    'message' => "Un nouveau rendez-vous a été planifié le {$dateFormatee} de {$heure_debut} à {$heure_fin} avec le patient {$user->prenom} {$user->nom}. Motif: {$validated['motif']}",
                    'dateEnvoi' => now(),
                    'typeLecture' => 0, // 0 = non lu
                    'medecin_id' => $validated['medecin_id'],
                    'user_id' => $medecin->utilisateur->id,
                ]);
            }
            
            return redirect()->route('patient.appointments')
                ->with('success', 'Votre demande de rendez-vous a été enregistrée avec succès. Vous recevrez une confirmation par email.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du rendez-vous', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre rendez-vous. Veuillez réessayer.');
        }
    }
    
    /**
     * Affiche les images DICOM du patient
     */
    public function dicomViewer()
    {
        $user = auth()->user();
        $patient = $user->patient;
        
        return view('patient.dicom.viewer', compact('patient'));
    }
    
    /**
     * Récupère les études d'imagerie d'un patient
     * 
     * @deprecated Cette méthode est conservée pour compatibilité
     * Utiliser les méthodes spécifiques d'imagerie à la place
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyStudies()
    {
        // Méthode laissée vide pour compatibilité
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'La fonctionnalité DICOM n\'est plus disponible.'
        ]);
    }
    
    /**
     * Récupère les images d'une étude (méthode de compatibilité)
     * 
     * @deprecated Cette méthode est conservée pour compatibilité
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudyImages(Request $request, $studyId)
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'La fonctionnalité DICOM n\'est plus disponible.'
        ]);
    }
    
    /**
     * Récupère l'aperçu d'une image (méthode de compatibilité)
     * 
     * @deprecated Cette méthode est conservée pour compatibilité
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImagePreview($instanceId)
    {
        return response()->json([
            'success' => false,
            'message' => 'La fonctionnalité de prévisualisation DICOM n\'est plus disponible.'
        ], 404);
    }
    
    /**
     * Récupère une image complète (méthode de compatibilité)
     * 
     * @deprecated Cette méthode est conservée pour compatibilité
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImage($instanceId)
    {
        return response()->json([
            'success' => false,
            'message' => 'La fonctionnalité de récupération d\'images DICOM n\'est plus disponible.'
        ], 404);
    }
}

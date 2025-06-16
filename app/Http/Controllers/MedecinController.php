<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
// Removed unused User import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use App\Models\Rendez_Vous;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Examen;
use App\Models\Facture;
use PDF;

class MedecinController extends Controller
{
    public function index()
    {
        $medecins = Utilisateur::where('role', 'MEDECIN')
            ->paginate(10);

        $specialites = config('specialites.medicales');

        return view('admin.medecins.index', compact('medecins', 'specialites'));
    }

    public function create()
    {
        return view('admin.medecins.create', [
            'specialites' => config('specialites.medicales')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:utilisateurs,email',
            'specialite' => 'required|string',
            'telephone' => 'required|string|max:20',
            'mot_de_passe' => 'required|string|min:6|confirmed'
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role' => 'MEDECIN',
            'telephone' => $request->telephone,
            //'specialite' => $request->specialite // Removed because 'specialite' is not a column in utilisateurs table
        ]);

        $matricule = 'MED' . time() . rand(100, 999);

        $specialites = array_flip(config('specialites.medicales'));
        $specialiteKey = $specialites[$request->specialite] ?? 'GENERALISTE';

        \App\Models\Medecin::create([
            'matricule' => $matricule,
            'specialite' => $specialiteKey,
            'utilisateur_id' => $utilisateur->id,
        ]);

        return redirect()->route('admin.medecins.index')
            ->with('success', 'Médecin créé avec succès');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->medecin) {
            abort(403, 'Accès refusé : utilisateur non associé à un médecin.');
        }

        $medecinId = $user->medecin->id;
        $dateDebut = now()->subDays(30); // Par défaut, 30 derniers jours
        $dateFin = now();

        // Statistiques de base
        $statistiques = [
            'consultations' => Consultation::where('medecin_id', $medecinId)
                ->whereBetween('date_consultation', [$dateDebut, $dateFin])
                ->count(),
            'patients' => Patient::whereHas('consultations', function($query) use ($medecinId, $dateDebut, $dateFin) {
                $query->where('medecin_id', $medecinId)
                    ->whereBetween('date_consultation', [$dateDebut, $dateFin]);
            })->count(),
            'rendez_vous' => Rendez_Vous::where('medecin_id', $medecinId)
                ->whereBetween('date_rendez_vous', [$dateDebut, $dateFin])
                ->count(),
        ];

        // Calcul des évolutions par rapport à la période précédente
        $periodePrecedenteDebut = $dateDebut->copy()->subDays($dateFin->diffInDays($dateDebut));
        $periodePrecedenteFin = $dateDebut;

        $statistiques['evolution_consultations'] = $this->calculerEvolution(
            Consultation::where('medecin_id', $medecinId)
                ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin])
                ->count(),
            $statistiques['consultations']
        );

        $statistiques['evolution_patients'] = $this->calculerEvolution(
            Patient::whereHas('consultations', function($query) use ($medecinId, $periodePrecedenteDebut, $periodePrecedenteFin) {
                $query->where('medecin_id', $medecinId)
                    ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin]);
            })->count(),
            $statistiques['patients']
        );

        $statistiques['evolution_rendez_vous'] = $this->calculerEvolution(
            Rendez_Vous::where('medecin_id', $medecinId)
                ->whereBetween('date_rendez_vous', [$periodePrecedenteDebut, $periodePrecedenteFin])
                ->count(),
            $statistiques['rendez_vous']
        );

        // Données pour les graphiques
        $statistiques['labels'] = $this->genererLabelsDates($dateDebut, $dateFin);
        $statistiques['consultations_par_jour'] = $this->getConsultationsParJour($medecinId, $dateDebut, $dateFin);
        $statistiques['types_consultations_labels'] = ['Planifiées', 'En cours', 'Terminées', 'Autres'];
        $statistiques['types_consultations_data'] = $this->getTypesConsultations($medecinId, $dateDebut, $dateFin);

        // Calcul du chiffre d'affaires
        $statistiques['chiffre_affaires'] = Consultation::where('medecin_id', $medecinId)
            ->whereBetween('date_consultation', [$dateDebut, $dateFin])
            ->sum('montant');

        // Calcul de l'évolution du chiffre d'affaires
        $chiffreAffairesPrecedent = Consultation::where('medecin_id', $medecinId)
            ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin])
            ->sum('montant');

        $statistiques['evolution_chiffre_affaires'] = $this->calculerEvolution(
            $chiffreAffairesPrecedent,
            $statistiques['chiffre_affaires']
        );

        // Rendez-vous du jour
        $rendezVous = Rendez_Vous::where('medecin_id', $medecinId)
            ->whereDate('date_rendez_vous', now())
            ->with('patient')
            ->orderBy('heure_debut', 'asc')
            ->get();

        // Dernières consultations
        $consultations = Consultation::where('medecin_id', $medecinId)
            ->with('patient')
            ->orderBy('date_consultation', 'desc')
            ->take(5)
            ->get();

        return view('medecin.dashboard', compact('statistiques', 'rendezVous', 'consultations'));
    }

    public function rendezVousIndex()
    {
        $medecin = Auth::user()->medecin;
        $rendezVous = Rendez_Vous::where('medecin_id', $medecin->id)
            ->with(['patient.utilisateur', 'patient'])
            ->orderBy('date_rendez_vous', 'desc')
            ->paginate(10);

        return view('medecin.rendez-vous.index', compact('rendezVous'));
    }

    public function rendezVousCreate()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.rendez-vous.create', compact('patients'));
    }

    public function rendezVousStore(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_rendez_vous' => 'required|date|after:now',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'motif' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $rendezVous = new Rendez_Vous();
        $rendezVous->medecin_id = Auth::user()->medecin->id;
        $rendezVous->patient_id = $request->patient_id;
        $rendezVous->date_rendez_vous = $request->date_rendez_vous;
        $rendezVous->heure_debut = $request->heure_debut;
        $rendezVous->heure_fin = $request->heure_fin;
        $rendezVous->motif = $request->motif;
        $rendezVous->notes = $request->notes;
        $rendezVous->save();

        return redirect()->route('medecin.rendez-vous.index')
            ->with('success', 'Rendez-vous créé avec succès');
    }

    public function rendezVousShow(Rendez_Vous $rendezVous)
    {
        $this->authorizeRendezVous($rendezVous);
        return view('medecin.rendez-vous.show', compact('rendezVous'));
    }

    public function rendezVousEdit(Rendez_Vous $rendezVous)
    {
        $this->authorizeRendezVous($rendezVous);
        $patients = Patient::all();
        return view('medecin.rendez-vous.edit', compact('rendezVous', 'patients'));
    }

    public function rendezVousUpdate(Request $request, Rendez_Vous $rendezVous)
    {
        $this->authorizeRendezVous($rendezVous);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_rendez_vous' => 'required|date|after:now',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'motif' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $rendezVous->update([
            'patient_id' => $request->patient_id,
            'date_rendez_vous' => $request->date_rendez_vous,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'motif' => $request->motif,
            'notes' => $request->notes
        ]);

        return redirect()->route('medecin.rendez-vous.index')
            ->with('success', 'Rendez-vous mis à jour avec succès');
    }

    public function rendezVousDestroy(Rendez_Vous $rendezVous)
    {
        $this->authorizeRendezVous($rendezVous);
        $rendezVous->delete();

        return redirect()->route('medecin.rendez-vous.index')
            ->with('success', 'Rendez-vous supprimé avec succès');
    }

    private function authorizeRendezVous(Rendez_Vous $rendezVous)
    {
        if ($rendezVous->medecin_id !== Auth::user()->medecin->id) {
            abort(403, 'Non autorisé');
        }
    }

    // Méthodes pour les consultations
    public function consultationsIndex()
    {
        $medecin = Auth::user()->medecin;
        $consultations = Consultation::where('medecin_id', $medecin->id)
            ->with(['patient.utilisateur'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);

        return view('medecin.consultations.index', compact('consultations'));
    }

    public function consultationsCreate()
    {
        $patients = Patient::all();
        return view('medecin.consultations.create', compact('patients'));
    }

    public function consultationsStore(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string|max:255',
            'symptomes' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'traitement' => 'nullable|string',
            'observations' => 'nullable|string',
            'statut' => 'required|in:PLANIFIE,EN_COURS,TERMINE'
        ]);

        $consultation = new Consultation();
        $consultation->medecin_id = Auth::user()->medecin->id;
        $consultation->patient_id = $request->patient_id;
        $consultation->date_consultation = $request->date_consultation;
        $consultation->motif = $request->motif;
        $consultation->symptomes = $request->symptomes;
        $consultation->diagnostic = $request->diagnostic;
        $consultation->traitement = $request->traitement;
        $consultation->observations = $request->observations;
        $consultation->statut = $request->statut;
        $consultation->save();

        return redirect()->route('medecin.consultations.index')
            ->with('success', 'Consultation créée avec succès');
    }

    public function consultationsShow(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        return view('medecin.consultations.show', compact('consultation'));
    }

    public function consultationsEdit(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        $patients = Patient::all();
        return view('medecin.consultations.edit', compact('consultation', 'patients'));
    }

    public function consultationsUpdate(Request $request, Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string|max:255',
            'symptomes' => 'required|string',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $consultation->update([
            'patient_id' => $request->patient_id,
            'date_consultation' => $request->date_consultation,
            'motif' => $request->motif,
            'symptomes' => $request->symptomes,
            'diagnostic' => $request->diagnostic,
            'traitement' => $request->traitement,
            'observations' => $request->observations
        ]);

        return redirect()->route('medecin.consultations.index')
            ->with('success', 'Consultation mise à jour avec succès');
    }

    public function consultationsDestroy(Consultation $consultation)
    {
        $this->authorizeConsultation($consultation);
        $consultation->delete();

        return redirect()->route('medecin.consultations.index')
            ->with('success', 'Consultation supprimée avec succès');
    }

    private function authorizeConsultation(Consultation $consultation)
    {
        if ($consultation->medecin_id !== Auth::user()->medecin->id) {
            abort(403, 'Non autorisé');
        }
    }

    // Méthodes pour les examens médicaux
    public function examensIndex()
    {
        $medecin = Auth::user()->medecin;
        $examens = Examen::where('medecin_id', $medecin->id)
            ->with(['patient.utilisateur'])
            ->orderBy('date_examen', 'desc')
            ->paginate(10);

        return view('medecin.examens.index', compact('examens'));
    }

    public function examensCreate()
    {
        $patients = Patient::all();
        return view('medecin.examens.create', compact('patients'));
    }

    public function examensStore(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_examen' => 'required|date',
            'type_examen' => 'required|string|max:255',
            'description' => 'required|string',
            'resultats' => 'required|string',
            'conclusion' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $examen = new Examen();
        $examen->medecin_id = Auth::user()->medecin->id;
        $examen->patient_id = $request->patient_id;
        $examen->date_examen = $request->date_examen;
        $examen->type_examen = $request->type_examen;
        $examen->description = $request->description;
        $examen->resultats = $request->resultats;
        $examen->conclusion = $request->conclusion;
        $examen->observations = $request->observations;
        $examen->save();

        return redirect()->route('medecin.examens.index')
            ->with('success', 'Examen créé avec succès');
    }

    public function examensShow(Examen $examen)
    {
        $this->authorizeExamen($examen);
        return view('medecin.examens.show', compact('examen'));
    }

    public function examensEdit(Examen $examen)
    {
        $this->authorizeExamen($examen);
        $patients = Patient::all();
        return view('medecin.examens.edit', compact('examen', 'patients'));
    }

    public function examensUpdate(Request $request, Examen $examen)
    {
        $this->authorizeExamen($examen);
        
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_examen' => 'required|date',
            'type_examen' => 'required|string|max:255',
            'description' => 'required|string',
            'resultats' => 'required|string',
            'conclusion' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $examen->update([
            'patient_id' => $request->patient_id,
            'date_examen' => $request->date_examen,
            'type_examen' => $request->type_examen,
            'description' => $request->description,
            'resultats' => $request->resultats,
            'conclusion' => $request->conclusion,
            'observations' => $request->observations
        ]);

        return redirect()->route('medecin.examens.index')
            ->with('success', 'Examen mis à jour avec succès');
    }

    public function examensDestroy(Examen $examen)
    {
        $this->authorizeExamen($examen);
        $examen->delete();

        return redirect()->route('medecin.examens.index')
            ->with('success', 'Examen supprimé avec succès');
    }

    private function authorizeExamen(Examen $examen)
    {
        if ($examen->medecin_id !== auth()->user()->medecin->id) {
            abort(403, 'Accès non autorisé à cet examen.');
        }
    }

    public function statistiques()
    {
        $medecin = Auth::user()->medecin;
        $dateDebut = request()->get('periode', 30); // Par défaut, 30 derniers jours
        $dateFin = now();
        $dateDebut = now()->subDays($dateDebut);

        // Statistiques de base
        $statistiques = [
            'consultations' => Consultation::where('medecin_id', $medecin->id)
                ->whereBetween('date_consultation', [$dateDebut, $dateFin])
                ->count(),
            'patients' => Patient::whereHas('consultations', function($query) use ($medecin, $dateDebut, $dateFin) {
                $query->where('medecin_id', $medecin->id)
                    ->whereBetween('date_consultation', [$dateDebut, $dateFin]);
            })->count(),
            'rendez_vous' => Rendez_Vous::where('medecin_id', $medecin->id)
                ->whereBetween('date_rendez_vous', [$dateDebut, $dateFin])
                ->count(),
        ];

        // Calcul des évolutions par rapport à la période précédente
        $periodePrecedenteDebut = $dateDebut->copy()->subDays($dateFin->diffInDays($dateDebut));
        $periodePrecedenteFin = $dateDebut;

        $statistiques['evolution_consultations'] = $this->calculerEvolution(
            Consultation::where('medecin_id', $medecin->id)
                ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin])
                ->count(),
            $statistiques['consultations']
        );

        $statistiques['evolution_patients'] = $this->calculerEvolution(
            Patient::whereHas('consultations', function($query) use ($medecin, $periodePrecedenteDebut, $periodePrecedenteFin) {
                $query->where('medecin_id', $medecin->id)
                    ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin]);
            })->count(),
            $statistiques['patients']
        );

        $statistiques['evolution_rendez_vous'] = $this->calculerEvolution(
            Rendez_Vous::where('medecin_id', $medecin->id)
                ->whereBetween('date_rendez_vous', [$periodePrecedenteDebut, $periodePrecedenteFin])
                ->count(),
            $statistiques['rendez_vous']
        );

        // Données pour les graphiques
        $statistiques['labels'] = $this->genererLabelsDates($dateDebut, $dateFin);
        $statistiques['consultations_par_jour'] = $this->getConsultationsParJour($medecin->id, $dateDebut, $dateFin);
        $statistiques['types_consultations_labels'] = ['Planifiées', 'En cours', 'Terminées', 'Autres'];
        $statistiques['types_consultations_data'] = $this->getTypesConsultations($medecin->id, $dateDebut, $dateFin);
        $statistiques['age_labels'] = ['0-18', '19-30', '31-50', '51-70', '70+'];
        $statistiques['age_data'] = $this->getRepartitionAge($medecin->id, $dateDebut, $dateFin);
        $statistiques['mois_labels'] = $this->getMoisLabels();
        $statistiques['chiffre_affaires_mensuel'] = $this->getChiffreAffairesMensuel($medecin->id);
        
        // Ajout du chiffre d'affaires total
        $statistiques['chiffre_affaires'] = Consultation::where('medecin_id', $medecin->id)
            ->whereBetween('date_consultation', [$dateDebut, $dateFin])
            ->sum('montant');
            
        // Calcul de l'évolution du chiffre d'affaires
        $chiffreAffairesPrecedent = Consultation::where('medecin_id', $medecin->id)
            ->whereBetween('date_consultation', [$periodePrecedenteDebut, $periodePrecedenteFin])
            ->sum('montant');
            
        $statistiques['evolution_chiffre_affaires'] = $this->calculerEvolution(
            $chiffreAffairesPrecedent,
            $statistiques['chiffre_affaires']
        );

        return view('medecin.statistiques.index', compact('statistiques'));
    }

    private function calculerEvolution($ancienneValeur, $nouvelleValeur)
    {
        if ($ancienneValeur === 0) return 100;
        return round((($nouvelleValeur - $ancienneValeur) / $ancienneValeur) * 100, 1);
    }

    private function genererLabelsDates($dateDebut, $dateFin)
    {
        $labels = [];
        $date = $dateDebut->copy();
        while ($date <= $dateFin) {
            $labels[] = $date->format('d/m');
            $date->addDay();
        }
        return $labels;
    }

    private function getConsultationsParJour($medecinId, $dateDebut, $dateFin)
    {
        $consultations = Consultation::where('medecin_id', $medecinId)
            ->whereBetween('date_consultation', [$dateDebut, $dateFin])
            ->selectRaw('DATE(date_consultation) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $resultat = [];
        $date = $dateDebut->copy();
        while ($date <= $dateFin) {
            $resultat[] = $consultations[$date->format('Y-m-d')] ?? 0;
            $date->addDay();
        }
        return $resultat;
    }

    private function getTypesConsultations($medecinId, $dateDebut, $dateFin)
    {
        // Puisque la colonne 'type' a été supprimée, nous retournons des données statiques
        // ou nous pouvons utiliser le statut de la consultation comme alternative
        $consultations = Consultation::where('medecin_id', $medecinId)
            ->whereBetween('date_consultation', [$dateDebut, $dateFin])
            ->get();

        return [
            $consultations->where('statut', 'PLANIFIE')->count(),
            $consultations->where('statut', 'EN_COURS')->count(),
            $consultations->where('statut', 'TERMINE')->count(),
            0 // Pour maintenir la compatibilité avec le graphique existant
        ];
    }

    private function getRepartitionAge($medecinId, $dateDebut, $dateFin)
    {
        $patients = Patient::whereHas('consultations', function($query) use ($medecinId, $dateDebut, $dateFin) {
            $query->where('medecin_id', $medecinId)
                ->whereBetween('date_consultation', [$dateDebut, $dateFin]);
        })->get();

        $repartition = [0, 0, 0, 0, 0];
        foreach ($patients as $patient) {
            // Vérification de la présence de date_naissance avant d'accéder à age
            if ($patient->date_naissance) {
                $age = $patient->date_naissance->age;
                if ($age <= 18) $repartition[0]++;
                elseif ($age <= 30) $repartition[1]++;
                elseif ($age <= 50) $repartition[2]++;
                elseif ($age <= 70) $repartition[3]++;
                else $repartition[4]++;
            } else {
                // Si pas de date de naissance, on l'ignore ou on peut le placer dans une catégorie spécifique
                // Ici on l'ignore simplement
            }
        }
        return $repartition;
    }

    private function getMoisLabels()
    {
        $mois = [];
        for ($i = 0; $i < 12; $i++) {
            $mois[] = now()->subMonths($i)->format('M Y');
        }
        return array_reverse($mois);
    }

    private function getChiffreAffairesMensuel($medecinId)
    {
        $chiffreAffaires = [];
        for ($i = 0; $i < 12; $i++) {
            $dateDebut = now()->subMonths($i)->startOfMonth();
            $dateFin = now()->subMonths($i)->endOfMonth();
            
            $chiffreAffaires[] = Consultation::where('medecin_id', $medecinId)
                ->whereBetween('date_consultation', [$dateDebut, $dateFin])
                ->sum('montant');
        }
        return array_reverse($chiffreAffaires);
    }

    public function parametres()
    {
        $medecin = Auth::user()->medecin;
        return view('medecin.parametres.index', compact('medecin'));
    }

    public function parametresUpdate(Request $request)
    {
        $medecin = Auth::user()->medecin;
        
        $request->validate([
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:utilisateurs,email,' . Auth::id(),
            'mot_de_passe_actuel' => 'required_with:mot_de_passe|current_password',
            'mot_de_passe' => 'nullable|min:6|confirmed',
        ]);

        // Mise à jour des informations de l'utilisateur
        Auth::user()->update([
            'telephone' => $request->telephone,
            'email' => $request->email,
        ]);

        // Mise à jour du mot de passe si fourni
        if ($request->filled('mot_de_passe')) {
            Auth::user()->update([
                'mot_de_passe' => Hash::make($request->mot_de_passe)
            ]);
        }

        return redirect()->route('medecin.parametres.index')
            ->with('success', 'Paramètres mis à jour avec succès');
    }

    public function profilUpdate(Request $request)
    {
        $utilisateur = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $utilisateur->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
        ]);

        $utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);

        // Traitement de la photo de profil si fournie
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'profile_' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $filename);
            $utilisateur->update(['photo' => '/storage/photos/' . $filename]);
        }

        return redirect()->route('medecin.parametres.index')
            ->with('success', 'Profil mis à jour avec succès');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'mot_de_passe' => Hash::make($request->password)
        ]);

        return redirect()->route('medecin.parametres.index')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }

    public function preferencesUpdate(Request $request)
    {
        $medecin = Auth::user()->medecin;
        
        $request->validate([
            'theme' => 'nullable|string|in:clair,sombre,systeme',
            'notifications' => 'nullable|boolean',
            'langue' => 'nullable|string|in:fr,en',
        ]);

        // Mise à jour des préférences
        // Si vous avez une table préférences, vous pouvez les stocker là
        // Sinon, vous pouvez les stocker dans un champ JSON du modèle médecin
        $preferences = $medecin->preferences ?? [];
        $preferences['theme'] = $request->theme ?? 'clair';
        $preferences['notifications'] = $request->has('notifications');
        $preferences['langue'] = $request->langue ?? 'fr';

        $medecin->update(['preferences' => $preferences]);

        return redirect()->route('medecin.parametres.index')
            ->with('success', 'Préférences mises à jour avec succès');
    }
}

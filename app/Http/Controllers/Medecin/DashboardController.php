<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Examen;
use App\Models\ImageMedicale;
use App\Models\Prescription;
use App\Models\DossierMedical;
use App\Models\Notification;
use App\Models\Consultation;
use App\Models\Dossier;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $medecin = $user->medecinRelation; // Utilisation de la relation medecinRelation au lieu de medecin
        
        if (!$medecin) {
            // Rediriger ou gérer le cas où l'utilisateur n'est pas un médecin
            return redirect()->route('login')->with('error', 'Accès non autorisé. Vous devez être un médecin pour accéder à cette page.');
        }

        // Récupérer les patients du médecin via les dossiers
        $patientsIds = $medecin->dossiers()->pluck('patient_id')->unique();

        // Statistiques principales
        $stats = [
            'patients_actifs' => Patient::whereIn('id', $patientsIds)->count(),

            'consultations_aujourdhui' => $medecin->consultations()
                ->whereDate('date_consultation', today())
                ->count(),

            'dossiers_medicaux' => $medecin->dossiers()
                ->where('statut', 'ACTIF')
                ->count(),

            'rendezvous_a_venir' => $medecin->rendezVous()
                ->where('date_rendez_vous', '>', now())
                ->count(),

            // Données pour le graphique des consultations
            'mois' => [],
            'consultations_par_mois' => [],

            // Données pour le graphique des patients
            'patients_par_sexe' => [
                Patient::whereIn('id', $patientsIds)
                    ->whereHas('utilisateur', function($query) {
                        $query->where('sexe', 'M');
                    })
                    ->count(),
                Patient::whereIn('id', $patientsIds)
                    ->whereHas('utilisateur', function($query) {
                        $query->where('sexe', 'F');
                    })
                    ->count()
            ]
        ];

        // Récupérer les dossiers médicaux
        $dossiers_medicaux = $medecin->dossiers()
            ->where('statut', 'ACTIF')
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();
            
        // Statistiques des délégations
        $delegations = \App\Models\DelegationAcces::where('medecin_id', $medecin->id)
            ->with([
                'patient.utilisateur',
                'infirmier' // La relation infirmier retourne déjà l'utilisateur
            ])
            ->where('statut', 'active')
            ->where('date_fin', '>=', now())
            ->orderBy('date_fin')
            ->take(5)
            ->get();
            
        $stats['delegations_actives'] = $delegations->count();
        $stats['delegations'] = $delegations;

        // Rendez-vous récents
        $rendezvous = $medecin->rendezVous()
            ->with(['patient.utilisateur'])  // Charger la relation utilisateur avec le patient
            ->latest()
            ->take(5)
            ->get();

        // Préparer les données pour le graphique des consultations
        $consultationsParMois = $medecin->consultations()
            ->select(
                DB::raw('DATE_FORMAT(date_consultation, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as nombre')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        foreach ($consultationsParMois as $item) {
            $stats['mois'][] = Carbon::parse($item->mois)->format('F Y');
            $stats['consultations_par_mois'][] = $item->nombre;
        }

        // Récupérer les examens récents
        $examens = Examen::with(['dossierMedical.patient'])
            ->whereHas('dossierMedical', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'patients' => Patient::latest()
                ->take(5)
                ->get(),

            'examens' => Examen::with(['patient'])
                ->latest()
                ->take(5)
                ->get(),

            'imageries' => ImageMedicale::with(['dossierMedical.patient'])
                ->latest()
                ->take(5)
                ->get(),

            'prescriptions' => Prescription::with(['dossierMedical.patient'])
                ->latest()
                ->take(5)
                ->get(),
            'consultations' => $medecin->consultations()
                ->with('patient')
                ->latest()
                ->take(5)
                ->get(),
            'notifications' => auth()->user()->notifications()
                ->latest()
                ->take(10)
                ->get()
        ];

        return view('medecin.dashboard', [
            'stats' => $stats,
            'rendezvous' => $rendezvous,
            'dossiers_medicaux' => $dossiers_medicaux,
            'examens' => $examens,
            'data' => $data
        ]);
    }
} 
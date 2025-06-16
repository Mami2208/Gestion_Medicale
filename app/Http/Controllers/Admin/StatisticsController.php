<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\DossierMedical;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Affiche la page des statistiques générales du système
     */
    public function index()
    {
        // Statistiques des utilisateurs
        $totalUtilisateurs = Utilisateur::count();
        $utilisateursByRole = Utilisateur::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->get()
            ->pluck('total', 'role')
            ->toArray();
            
        // Statistiques des patients
        $totalPatients = Patient::count();
        $patientsParSexe = Patient::selectRaw('sexe, count(*) as total')
            ->groupBy('sexe')
            ->get()
            ->pluck('total', 'sexe')
            ->toArray();
            
        // Statistiques des médecins
        $totalMedecins = Medecin::count();
        $medecinsParSpecialite = Medecin::selectRaw('specialite, count(*) as total')
            ->groupBy('specialite')
            ->get()
            ->pluck('total', 'specialite')
            ->toArray();
            
        // Statistiques des consultations
        $totalConsultations = Consultation::count();
        $consultationsParMois = Consultation::selectRaw('MONTH(date_consultation) as mois, count(*) as total')
            ->whereYear('date_consultation', date('Y'))
            ->groupBy('mois')
            ->get()
            ->pluck('total', 'mois')
            ->toArray();
            
        // Statistiques des rendez-vous
        $totalRendezVous = RendezVous::count();
        $rendezVousAVenir = RendezVous::where('date', '>=', now()->format('Y-m-d'))->count();
        
        // Statistiques des dossiers médicaux
        $totalDossiers = DossierMedical::count();
            
        return view('admin.statistics.index', compact(
            'totalUtilisateurs', 
            'utilisateursByRole', 
            'totalPatients', 
            'patientsParSexe',
            'totalMedecins',
            'medecinsParSpecialite',
            'totalConsultations',
            'consultationsParMois',
            'totalRendezVous',
            'rendezVousAVenir',
            'totalDossiers'
        ));
    }
}

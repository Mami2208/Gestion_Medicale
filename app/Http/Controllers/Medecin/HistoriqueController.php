<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Examen;
use App\Models\Prescription;

class HistoriqueController extends Controller
{
    public function index()
    {
        // Logique pour afficher l'historique général
        // Vous devrez implémenter la récupération des données ici
        return view('medecin.historique.index');
    }

    public function showConsultation(Consultation $consultation)
    {
        // Logique pour afficher les détails d'une consultation spécifique
        return view('medecin.historique.consultation', compact('consultation'));
    }

    public function showExamen(Examen $examen)
    {
        // Logique pour afficher les détails d'un examen spécifique
        return view('medecin.historique.examen', compact('examen'));
    }

    public function showPrescription(Prescription $prescription)
    {
        // Logique pour afficher les détails d'une prescription spécifique
        return view('medecin.historique.prescription', compact('prescription'));
    }
} 
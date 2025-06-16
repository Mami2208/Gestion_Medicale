<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\DossierMedical;
use App\Models\Traitement;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['dossierMedical.patient.utilisateur', 'medecin.utilisateur'])
            ->orderBy('date_prescription', 'desc')
            ->paginate(10);

        $patients = Patient::with('utilisateur')
            ->join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->orderBy('utilisateurs.nom')
            ->select('patients.*')
            ->get();

        return view('medecin.prescriptions.index', compact('prescriptions', 'patients'));
    }

    public function create()
    {
        $patients = Patient::with('utilisateur')
            ->join('utilisateurs', 'patients.utilisateur_id', '=', 'utilisateurs.id')
            ->orderBy('utilisateurs.nom')
            ->select('patients.*')
            ->get();
        return view('medecin.prescriptions.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicament' => 'required|string',
            'posologie' => 'required|string',
            'frequence' => 'required|string',
            'duree_jours' => 'required|integer|min:1',
            'instructions' => 'required|string',
            'date_prescription' => 'required|date',
            'type_traitement' => 'required|string|in:MEDICAMENT,KINESITHERAPIE,PANSEMENT,SOINS_INFIRMIER,AUTRE',
            'description_traitement' => 'required|string',
        ]);

        // Récupérer l'ID du médecin connecté
        $medecinId = auth()->user()->medecin->id;
        
        // Récupérer le dossier médical du patient
        $dossier = DossierMedical::where('patient_id', $validated['patient_id'])->firstOrFail();

        // Créer d'abord le traitement
        $traitement = Traitement::create([
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $medecinId,
            'dossier_medical_id' => $dossier->id,
            'type_traitement' => $validated['type_traitement'],
            'description' => $validated['description_traitement'],
            'date_debut' => $validated['date_prescription'],
            'statut' => 'EN_COURS',
        ]);

        // Créer la prescription liée au traitement
        $prescription = Prescription::create([
            'dossier_medical_id' => $dossier->id,
            'traitement_id' => $traitement->id,
            'medicament' => $validated['medicament'],
            'posologie' => $validated['posologie'],
            'frequence' => $validated['frequence'],
            'duree_jours' => $validated['duree_jours'],
            'instructions' => $validated['instructions'],
            'date_prescription' => $validated['date_prescription'],
            'statut' => 'EN_COURS',
            'medecin_id' => $medecinId,
        ]);

        return redirect()->route('medecin.prescriptions.show', $prescription)
            ->with('success', 'Prescription créée avec succès');
    }

    public function show(Prescription $prescription)
    {
        // Charger les relations nécessaires
        $prescription->load('traitement', 'dossierMedical.patient.utilisateur', 'medecin.utilisateur');
        return view('medecin.prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        return view('medecin.prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'medicament' => 'required|string',
            'posologie' => 'required|string',
            'frequence' => 'required|string',
            'duree_jours' => 'required|integer|min:1',
            'instructions' => 'required|string',
            'date_prescription' => 'required|date',
            'type_traitement' => 'required|string|in:MEDICAMENT,KINESITHERAPIE,PANSEMENT,SOINS_INFIRMIER,AUTRE',
            'description_traitement' => 'required|string',
        ]);

        // Mettre à jour le traitement associé
        if ($prescription->traitement) {
            $prescription->traitement->update([
                'type_traitement' => $validated['type_traitement'],
                'description' => $validated['description_traitement'],
                'date_debut' => $validated['date_prescription'],
            ]);
        }

        // Mettre à jour la prescription
        $prescription->update([
            'medicament' => $validated['medicament'],
            'posologie' => $validated['posologie'],
            'frequence' => $validated['frequence'],
            'duree_jours' => $validated['duree_jours'],
            'instructions' => $validated['instructions'],
            'date_prescription' => $validated['date_prescription'],
        ]);

        return redirect()->route('medecin.prescriptions.show', $prescription)
            ->with('success', 'Prescription mise à jour avec succès');
    }
} 
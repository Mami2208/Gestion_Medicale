<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with(['patient.utilisateur'])
            ->where('medecin_id', Auth::user()->medecin->id)
            ->latest()
            ->paginate(10);

        return view('medecin.consultations.index', compact('consultations'));
    }

    public function create()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.consultations.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string|max:1000',
            'diagnostic' => 'nullable|string|max:2000',
            'traitement' => 'nullable|string|max:2000',
            'notes' => 'nullable|string',
            'statut' => 'required|in:planifiee,terminee,annulee'
        ]);

        $validated['medecin_id'] = Auth::user()->medecin->id;
        $consultation = Consultation::create($validated);

        return redirect()->route('medecin.consultations.show', $consultation->id)
            ->with('success', 'Consultation créée avec succès.');
    }

    public function show(Consultation $consultation)
    {
        $this->authorize('view', $consultation);
        
        $consultation->load(['patient.utilisateur', 'ordonnances', 'examens']);
        return view('medecin.consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation)
    {
        $this->authorize('update', $consultation);
        
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.consultations.edit', compact('consultation', 'patients'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $this->authorize('update', $consultation);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_consultation' => 'required|date',
            'motif' => 'required|string|max:1000',
            'diagnostic' => 'nullable|string|max:2000',
            'traitement' => 'nullable|string|max:2000',
            'notes' => 'nullable|string',
            'statut' => 'required|in:planifiee,terminee,annulee'
        ]);

        $consultation->update($validated);

        return redirect()->route('medecin.consultations.show', $consultation->id)
            ->with('success', 'Consultation mise à jour avec succès.');
    }

    public function destroy(Consultation $consultation)
    {
        $this->authorize('delete', $consultation);
        
        $consultation->delete();
        
        return redirect()->route('medecin.consultations.index')
            ->with('success', 'Consultation supprimée avec succès.');
    }
    
    /**
     * Affiche le visualiseur DICOM pour une consultation
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\View\View
     */
    public function dicomViewer(Consultation $consultation)
    {
        $this->authorize('view', $consultation);
        
        // Charger les données nécessaires
        $consultation->load(['patient.utilisateur', 'dicomStudies']);
        
        // Récupérer l'URL du serveur Orthanc depuis la configuration
        $orthancUrl = config('orthanc.url');
        
        // Formater la date de naissance si elle existe
        $dateNaissance = null;
        if (!empty($consultation->patient->date_naissance)) {
            try {
                $dateNaissance = \Carbon\Carbon::parse($consultation->patient->date_naissance);
            } catch (\Exception $e) {
                $dateNaissance = null;
            }
        }
        
        // Récupérer la liste des patients pour le sélecteur
        $patients = Patient::with('utilisateur')
            ->whereHas('consultations', function($query) {
                $query->where('medecin_id', Auth::user()->medecin->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Préparer les données pour la vue
        $study = (object)[
            'patient' => (object)[
                'user' => (object)[
                    'name' => $consultation->patient->utilisateur->nom_complet ?? 'N/A',
                ],
                'date_naissance' => $dateNaissance,
                'date_naissance_formatted' => $dateNaissance ? $dateNaissance->format('d/m/Y') : 'N/A',
                'numero_securite_sociale' => $consultation->patient->numero_securite_sociale ?? 'N/A',
            ],
            // Propriétés de l'étude
            'study_date' => $consultation->created_at ?? now(),
            'description' => 'Examen DICOM pour ' . ($consultation->patient->utilisateur->nom_complet ?? 'le patient'),
            'created_at' => $consultation->created_at ?? now(),
            'uploader' => (object)[
                'name' => Auth::user()->nom_complet ?? 'Système'
            ],
            'viewer_url' => $orthancUrl,
            'current_patient_id' => $consultation->patient_id
        ];
        
        return view('medecin.dicom.viewer', compact('study', 'patients'));
    }
}

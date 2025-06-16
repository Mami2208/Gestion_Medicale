<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\DicomStudy;
use App\Services\OrthancClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DicomController extends Controller
{
    protected $orthanc;

    public function __construct(OrthancClient $orthanc)
    {
        $this->orthanc = $orthanc;
    }

    /**
     * Affiche le formulaire d'upload d'images DICOM
     */
    public function create()
    {
        // Récupérer la liste des patients du médecin connecté via la table dossiers_medicaux
        $patients = Patient::whereHas('dossiers', function($query) {
                $query->where('medecin_id', auth()->id());
            })
            ->with('utilisateur') // Charger la relation utilisateur
            ->get();
            
        // Si aucun patient n'est trouvé, on récupère tous les patients (pour le débogage)
        if ($patients->isEmpty()) {
            $patients = Patient::with('utilisateur')->limit(50)->get();
        }
            
        return view('medecin.dicom.upload', compact('patients'));
    }

    /**
     * Stocke une nouvelle image DICOM
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dicom_file' => 'required|file|mimetypes:application/dicom,application/octet-stream|max:51200', // 50MB max
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $file = $request->file('dicom_file');
            
            // Envoyer le fichier vers Orthanc
            $uploadResult = $this->orthanc->uploadDicomFile($file);
            
            // Enregistrer les métadonnées dans la base de données
            $study = DicomStudy::create([
                'patient_id' => $request->patient_id,
                'study_uid' => $uploadResult['ID'],
                'study_date' => now(),
                'description' => $request->description,
                'study_data' => json_encode($uploadResult),
                'uploaded_by' => auth()->id(),
            ]);
            
            return redirect()
                ->route('medecin.dicom.view', $study->id)
                ->with('success', 'L\'image DICOM a été téléversée avec succès.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors du téléversement du fichier DICOM: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du téléversement du fichier. ' . $e->getMessage());
        }
    }

    /**
     * Affiche la visionneuse DICOM pour une étude spécifique
     */
    public function view($id)
    {
        // Activer le débogage des requêtes SQL
        \DB::enableQueryLog();
        
        // Récupérer l'étude avec les relations nécessaires
        $study = DicomStudy::with(['patient.user', 'uploader'])
            ->where('id', $id)
            ->firstOrFail();
            
        // Vérifier que l'utilisateur a le droit de voir cette étude via la relation dossiers_medicaux
        $hasAccess = $study->patient->dossiers()
            ->where('medecin_id', auth()->id())
            ->exists();
            
        if (!$hasAccess) {
            abort(403, 'Accès non autorisé à cette étude.');
        }
        
        // Récupérer la liste des patients du médecin connecté via les dossiers médicaux
        $patients = Patient::whereHas('dossiers', function($query) {
                $query->where('medecin_id', auth()->id());
            })
            ->with(['utilisateur']) // Charger la relation utilisateur
            ->get();
            
        // Afficher les requêtes SQL exécutées
        $queries = \DB::getQueryLog();
        \Log::debug('Requêtes SQL exécutées :', $queries);
        
        // Si aucun patient n'est trouvé, on récupère les 50 premiers patients (pour le débogage)
        if ($patients->isEmpty()) {
            $patients = Patient::with('user')->limit(50)->get();
        }
            
        // Si aucun patient n'est trouvé via les dossiers médicaux, on récupère tous les patients
        // (pour éviter une erreur si la relation n'est pas encore configurée)
        if ($patients->isEmpty()) {
            $patients = Patient::with('user')->limit(50)->get();
        }
        
        // Récupérer les détails de l'étude depuis Orthanc
        try {
            $studyDetails = $this->orthanc->getStudyDetails($study->study_uid);
            $instances = $this->orthanc->getStudyInstances($study->study_uid);
            
            // Fusionner les données de l'étude avec les détails d'Orthanc
            $studyData = array_merge($study->toArray(), $studyDetails, [
                'patient_id' => $study->patient_id, // S'assurer que patient_id est défini
                'patient' => $study->patient, // Inclure l'objet patient complet
                'viewer_url' => config('orthanc.web_viewer_url') . "#study?study=" . $study->study_uid,
            ]);
            
            // Débogage : vérifier la structure des données
            \Log::debug('Données de l\'étude:', $studyData);
            
            return view('medecin.dicom.viewer', [
                'study' => (object) $studyData,
                'patients' => $patients,
                'instances' => $instances,
                'currentPatientId' => $study->patient_id, // Ajout d'une variable séparée pour le patient_id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des détails de l\'étude: ' . $e->getMessage());
            return back()->with('error', 'Impossible de charger l\'étude DICOM: ' . $e->getMessage());
        }
    }

    /**
     * Liste toutes les études DICOM d'un patient
     */
    public function index(Request $request)
    {
        // Récupérer les IDs des patients qui ont un dossier médical pour ce médecin
        $patientIds = \App\Models\Dossiers_Medicaux::where('medecin_id', auth()->id())
            ->pluck('patient_id')
            ->toArray();
            
        // Si aucun patient trouvé, on prend les 50 premiers patients
        if (empty($patientIds)) {
            $patientIds = \App\Models\Patient::limit(50)->pluck('id')->toArray();
        }
        
        $query = DicomStudy::with(['patient.user', 'uploader'])
            ->whereIn('patient_id', $patientIds);
            
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        $studies = $query->latest()->paginate(10);
        
        return view('medecin.dicom.index', compact('studies'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\DossierMedical;
use App\Models\Consultation;
use App\Services\OrthancClient;

class DicomController extends Controller
{
    protected $orthanc;

    public function __construct(OrthancClient $orthanc)
    {
        $this->orthanc = $orthanc;
        $this->middleware('auth');
    }

    /**
     * Affiche la visionneuse DICOM
     */
    public function viewer($id = null)
    {
        try {
            $study = null;
            $instances = [];
            $webViewerUrl = config('services.orthanc.web_viewer_url', 'http://localhost:8042/app/explorer.html');
            
            if ($id) {
                // Récupérer les détails de l'étude depuis Orthanc
                $study = $this->orthanc->getStudyDetails($id);
                
                if (!$study) {
                    return redirect()->route('dicom.studies')
                        ->with('error', 'Étude DICOM non trouvée.');
                }
                
                // Construire l'URL du visualiseur web Orthanc
                $webViewerUrl = rtrim($webViewerUrl, '/') . "#study?study=" . $study['id'];
                
                // Récupérer les instances de l'étude
                $instances = $this->orthanc->getStudyInstances($id);
            }
            
            // Récupérer les dossiers médicaux pour le menu déroulant (si nécessaire)
            $dossiers = DossierMedical::with('patient.user')
                ->whereHas('patient', function($query) {
                    $query->where('infirmier_id', Auth::id())
                          ->orWhereHas('consultations', function($q) {
                              $q->where('medecin_id', Auth::id());
                          });
                })
                ->latest()
                ->get();
            
            return view('dicom.viewer', [
                'study' => $study ? (object) $study : null,
                'instances' => $instances,
                'webViewerUrl' => $webViewerUrl,
                'dossiers' => $dossiers,
                'consultation_id' => request('consultation_id')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement de la visionneuse DICOM: ' . $e->getMessage());
            return redirect()->route('dicom.studies')
                ->with('error', 'Une erreur est survenue lors du chargement de la visionneuse DICOM.');
        }
    }

    /**
     * Affiche le formulaire de téléversement DICOM
     */
    public function showUploadForm()
    {
        $dossiers = DossierMedical::with('patient.user')
            ->whereHas('patient', function($query) {
                $query->where('infirmier_id', Auth::id())
                      ->orWhereHas('consultations', function($q) {
                          $q->where('medecin_id', Auth::id());
                      });
            })
            ->latest()
            ->get();
            
        return view('dicom.upload', compact('dossiers'));
    }

    /**
     * Traite le téléversement d'un fichier DICOM
     */
    public function upload(Request $request)
    {
        $request->validate([
            'dicom_file' => 'required|file|mimetypes:application/dicom,application/octet-stream|max:51200',
            'dossier_id' => 'nullable|exists:dossiers_medicaux,id'
        ]);

        try {
            $file = $request->file('dicom_file');
            $dossierId = $request->input('dossier_id');
            
            // Envoyer le fichier à Orthanc
            $result = $this->orthanc->uploadDicomFile($file);
            
            // Si un dossier médical est spécifié, associer l'étude au dossier
            if ($dossierId) {
                $dossier = DossierMedical::findOrFail($dossierId);
                // Ici, vous pourriez enregistrer la référence à l'étude Orthanc
                // dans votre base de données, par exemple dans une table pivot
            }
            
            return redirect()->route('dicom.viewer', ['id' => $result['study_id'] ?? null])
                ->with('success', 'Fichier DICOM téléversé avec succès.');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors du téléversement DICOM: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du téléversement du fichier DICOM.');
        }
    }

    /**
     * Affiche la liste des études DICOM
     */
    public function studies()
    {
        try {
            $studies = $this->orthanc->getStudies();
            
            return view('dicom.studies', [
                'studies' => $studies
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des études DICOM: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la récupération des études DICOM.');
        }
    }

    /**
     * Affiche les images d'une étude DICOM
     */
    public function images($studyId)
    {
        try {
            $study = $this->orthanc->getStudyDetails($studyId);
            $instances = $this->orthanc->getStudyInstances($studyId);
            
            return response()->json([
                'study' => $study,
                'instances' => $instances
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des images DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des images'], 500);
        }
    }

    /**
     * Affiche un aperçu d'une instance DICOM
     */
    public function preview($instanceId)
    {
        try {
            $imageData = $this->orthanc->getInstancePreview($instanceId);
            
            return response($imageData)
                ->header('Content-Type', 'image/jpeg');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération de l\'aperçu DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la génération de l\'aperçu'], 500);
        }
    }

    /**
     * Exporte une étude DICOM
     */
    public function export($studyId)
    {
        try {
            $zipPath = $this->orthanc->exportStudy($studyId);
            
            return response()->download($zipPath, "etude-{$studyId}.zip")
                ->deleteFileAfterSend(true);
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'exportation de l\'étude DICOM: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de l\'exportation de l\'étude DICOM.');
        }
    }

    /**
     * Met à jour les métadonnées d'une étude DICOM
     */
    public function update(Request $request, $studyId)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_id' => 'required|string|max:64',
            'patient_birth_date' => 'nullable|date',
            'study_description' => 'nullable|string|max:255',
        ]);

        try {
            $this->orthanc->updateStudyMetadata($studyId, $validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Métadonnées mises à jour avec succès.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour des métadonnées DICOM: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour des métadonnées.'
            ], 500);
        }
    }

    /**
     * Supprime une étude DICOM
     */
    public function destroy($studyId)
    {
        try {
            $this->orthanc->deleteStudy($studyId);
            
            return response()->json([
                'success' => true,
                'message' => 'Étude DICOM supprimée avec succès.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de l\'étude DICOM: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de l\'étude.'
            ], 500);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OrthancClient
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $webViewerUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('orthanc.base_url', 'http://localhost:8042'), '/');
        $this->username = config('orthanc.username');
        $this->password = config('orthanc.password');
        $this->webViewerUrl = config('orthanc.web_viewer_url', 'http://localhost:8042/app/explorer.html');
    }

    /**
     * Effectue une requête HTTP vers l'API Orthanc
     */
    protected function request($method, $endpoint, $data = null, $headers = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        $options = [
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], $headers),
            'auth' => $this->username ? [$this->username, $this->password] : null,
            'verify' => false, // Désactive la vérification SSL en développement
        ];
        
        if ($data !== null) {
            if (isset($headers['Content-Type']) && $headers['Content-Type'] === 'application/dicom') {
                $options['body'] = $data;
            } else {
                $options['json'] = $data;
            }
        }
        
        try {
            $response = Http::withOptions($options)->$method($url);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Erreur Orthanc API', [
                'url' => $url,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            throw new \Exception("Erreur Orthanc API: " . ($response['Message'] ?? 'Erreur inconnue'));
            
        } catch (\Exception $e) {
            Log::error('Erreur de connexion à Orthanc', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Télécharge un fichier DICOM vers Orthanc
     */
    public function uploadDicomFile(UploadedFile $file)
    {
        $tempPath = $file->getRealPath();
        $content = file_get_contents($tempPath);
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/dicom',
        ])
        ->withBasicAuth($this->username, $this->password)
        ->withBody($content, 'application/dicom')
        ->post($this->baseUrl . '/instances');
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Échec du téléversement du fichier DICOM: ' . ($response['Message'] ?? 'Erreur inconnue'));
    }

    /**
     * Récupère la liste des études
     */
    public function getStudies($query = [])
    {
        return $this->request('GET', '/studies', $query);
    }

    /**
     * Récupère les détails d'une étude
     */
    public function getStudyDetails($studyId)
    {
        return $this->request('GET', "/studies/{$studyId}");
    }

    /**
     * Récupère les instances d'une étude
     */
    public function getStudyInstances($studyId)
    {
        $study = $this->getStudyDetails($studyId);
        
        if (empty($study['Instances'])) {
            return [];
        }
        
        $instances = [];
        foreach ($study['Instances'] as $instanceId) {
            $instance = $this->request('GET', "/instances/{$instanceId}/simplified-tags");
            if ($instance) {
                $instances[] = $instance;
            }
        }
        
        // Trier par position d'image si disponible
        usort($instances, function($a, $b) {
            $aPos = $a['InstanceNumber'] ?? 0;
            $bPos = $b['InstanceNumber'] ?? 0;
            return $aPos <=> $bPos;
        });
        
        return $instances;
    }

    /**
     * Récupère un aperçu d'une instance
     */
    public function getInstancePreview($instanceId)
    {
        $url = $this->baseUrl . "/instances/{$instanceId}/preview";
        
        $response = Http::withBasicAuth($this->username, $this->password)
            ->get($url);
            
        if ($response->successful()) {
            return $response->body();
        }
        
        throw new \Exception('Impossible de récupérer l\'aperçu de l\'instance');
    }

    /**
     * Exporte une étude au format ZIP
     */
    public function exportStudy($studyId)
    {
        // Créer un fichier ZIP temporaire
        $tempFile = tempnam(sys_get_temp_dir(), 'orthanc_export_') . '.zip';
        
        // Télécharger l'export ZIP depuis Orthanc
        $url = $this->baseUrl . "/studies/{$studyId}/archive";
        
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withOptions([
                'sink' => $tempFile,
                'verify' => false,
            ])
            ->get($url);
            
        if ($response->successful()) {
            return $tempFile;
        }
        
        @unlink($tempFile);
        throw new \Exception('Échec de l\'exportation de l\'étude');
    }

    /**
     * Met à jour les métadonnées d'une étude
     */
    public function updateStudyMetadata($studyId, $metadata)
    {
        // Récupérer les tags actuels de l'étude
        $study = $this->getStudyDetails($studyId);
        $patientId = $study['MainDicomTags']['PatientID'] ?? null;
        
        if (!$patientId) {
            throw new \Exception('Impossible de trouver l\'ID du patient pour cette étude');
        }
        
        // Mettre à jour les informations du patient
        $patientUpdate = [
            'ID' => $metadata['patient_id'] ?? $patientId,
            'PatientName' => $metadata['patient_name'] ?? '',
            'PatientBirthDate' => $metadata['patient_birth_date'] ?? '',
        ];
        
        $this->request('PUT', "/patients/{$patientId}", $patientUpdate);
        
        // Mettre à jour la description de l'étude si fournie
        if (!empty($metadata['study_description'])) {
            $studyUpdate = [
                'StudyDescription' => $metadata['study_description']
            ];
            $this->request('PUT', "/studies/{$studyId}", $studyUpdate);
        }
        
        return true;
    }

    /**
     * Supprime une étude
     */
    public function deleteStudy($studyId)
    {
        // Récupérer les instances de l'étude
        $study = $this->getStudyDetails($studyId);
        
        if (empty($study['Instances'])) {
            throw new \Exception('Aucune instance trouvée pour cette étude');
        }
        
        // Supprimer chaque instance
        foreach ($study['Instances'] as $instanceId) {
            $this->request('DELETE', "/instances/{$instanceId}");
        }
        
        return true;
    }

    /**
     * Vérifie la connexion à Orthanc
     */
    public function checkConnection()
    {
        try {
            $response = $this->request('GET', '/system');
            return !empty($response['Name']) && $response['Name'] === 'Orthanc';
        } catch (\Exception $e) {
            return false;
        }
    }
}

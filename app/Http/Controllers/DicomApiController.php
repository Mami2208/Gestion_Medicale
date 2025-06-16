<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DicomApiController extends Controller
{
    protected $orthancUrl;
    protected $auth;

    public function __construct()
    {
        $this->orthancUrl = config('services.orthanc.url');
        $this->auth = [
            config('services.orthanc.username'),
            config('services.orthanc.password')
        ];
    }

    /**
     * Récupère les études DICOM d'un patient
     */
    public function getPatientStudies($patientId)
    {
        try {
            $response = Http::withBasicAuth(...$this->auth)
                ->get("{$this->orthancUrl}/patients/{$patientId}/studies");
                
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des études DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des études DICOM'], 500);
        }
    }

    /**
     * Récupère les séries d'une étude DICOM
     */
    public function getStudySeries($studyId)
    {
        try {
            $response = Http::withBasicAuth(...$this->auth)
                ->get("{$this->orthancUrl}/studies/{$studyId}/series");
                
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des séries DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des séries DICOM'], 500);
        }
    }

    /**
     * Récupère les instances d'une série DICOM
     */
    public function getSeriesInstances($seriesId)
    {
        try {
            $response = Http::withBasicAuth(...$this->auth)
                ->get("{$this->orthancUrl}/series/{$seriesId}/instances");
                
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des instances DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des instances DICOM'], 500);
        }
    }

    /**
     * Récupère une instance DICOM spécifique
     */
    public function getInstance($instanceId)
    {
        try {
            $response = Http::withBasicAuth(...$this->auth)
                ->get("{$this->orthancUrl}/instances/{$instanceId}");
                
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'instance DICOM: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération de l\'instance DICOM'], 500);
        }
    }
}

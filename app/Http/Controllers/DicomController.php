<?php

namespace App\Http\Controllers;

use App\Models\ImageDicom;
use App\Models\ImageMedicale;
use App\Policies\ImagePolicy;
use App\Services\OrthancService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DicomController extends Controller
{
    public function __construct(
        private OrthancService $orthanc
    ) {}

    public function show(string $orthancId)
    {
        $image = ImageMedicale::whereHas('dicom', fn($q) => $q->where('orthanc_id', $orthancId))
            ->firstOrFail();

        Gate::authorize('view', $image);

        return response($this->orthanc->getInstanceFile($orthancId))
            ->header('Content-Type', 'application/dicom');
    }

    public function index(Request $request)
    {
        return $request->user()->role === 'MEDECIN'
            ? ImageMedicale::with('dicom')->get()
            : $request->user()->patient->dossierMedical->images;
    }
}
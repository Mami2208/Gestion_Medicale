<?php

namespace App\Http\Controllers;

use App\Services\OrthancService;
use App\Models\ImageMedicale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Rules\DicomFile;

class DicomController extends Controller
{
    protected $orthancService;

    public function __construct(OrthancService $orthancService)
    {
        $this->orthancService = $orthancService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (auth()->user()->role === 'MEDECIN') {
            $images = ImageMedicale::with('dicom')->get();
        } else {
            $patient = auth()->user()->patient;
            if ($patient && $patient->dossierMedical) {
                $images = $patient->dossierMedical->images;
            } else {
                $images = collect(); // empty collection if no patient or dossierMedical
            }
        }

        return view('dashboard', compact('images'));
    }

    public function viewer($orthancId)
    {
        $image = ImageMedicale::whereHas('dicom', function($query) use ($orthancId) {
            $query->where('orthanc_id', $orthancId);
        })->firstOrFail();

        Gate::authorize('view', $image);

        $metadata = $this->orthancService->getInstanceTags($orthancId);

        return view('viewer', [
            'image' => $image,
            'metadata' => $metadata
        ]);
    }

/*
    public function uploadForm()
    {
        $dossiers = DossierMedical::with('patient')->get();
        return view('upload', compact('dossiers'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'dicom_file' => ['required', 'file', new DicomFile()],
            'dossier_id' => 'required|exists:dossiers_medicaux,id',
        ]);

        $dicomFile = $request->file('dicom_file');
        $dossierId = $request->input('dossier_id');

        // Upload to Orthanc
        $orthancId = $this->orthancService->uploadDicom($dicomFile);

        // Save in database
        $imageMedicale = new \App\Models\ImageMedicale();
        $imageMedicale->orthanc_id = $orthancId;
        $imageMedicale->dossier_medical_id = $dossierId;
        $imageMedicale->save();

        return redirect()->route('dicom.upload.form')->with('success', 'Fichier DICOM uploadé avec succès.');
    }
*/
}

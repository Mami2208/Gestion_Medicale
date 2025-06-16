<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\ImageMedicale;
use App\Models\Patient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagerieController extends Controller
{
    public function __construct()
    {
        // Initialisation du contrôleur
    }

    public function index()
    {
        $medecin = auth()->user()->medecin;
        $imageries = ImageMedicale::with(['dossierMedical.patient.utilisateur'])
            ->whereHas('dossierMedical', function($query) use ($medecin) {
                $query->where('medecin_id', $medecin->id);
            })
            ->latest()
            ->paginate(10);

        return view('medecin.imagerie.index', compact('imageries'));
    }

    public function create()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.imagerie.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'fichier_dicom' => 'required|file|mimes:dcm,dicom',
            'description' => 'nullable|string',
            'statut' => 'required|in:en_cours,termine,annule'
        ]);

        if ($request->hasFile('fichier_dicom')) {
            // TODO: Implémenter la logique d'upload des fichiers
        }

        $validated['medecin_id'] = auth()->user()->medecin->id;

        ImageMedicale::create($validated);

        return redirect()->route('medecin.imagerie.index')
            ->with('success', 'Imagerie créée avec succès.');
    }

    public function show(ImageMedicale $imagerie)
    {
        $this->authorize('view', $imagerie);
        
        return view('medecin.imagerie.show', compact('imagerie'));
    }

    public function edit(ImageMedicale $imagerie)
    {
        $this->authorize('update', $imagerie);
        $patients = Patient::with('utilisateur')->get();
        return view('medecin.imagerie.edit', compact('imagerie', 'patients'));
    }

    public function update(Request $request, ImageMedicale $imagerie)
    {
        $this->authorize('update', $imagerie);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|string|max:255',
            'date' => 'required|date',
            'fichier_dicom' => 'nullable|file|mimes:dcm,dicom',
            'description' => 'nullable|string',
            'statut' => 'required|in:en_cours,termine,annule'
        ]);

        if ($request->hasFile('fichier_dicom')) {
            // TODO: Implémenter la logique de mise à jour du fichier
        }

        $imagerie->update($validated);

        return redirect()->route('medecin.imagerie.index')
            ->with('success', 'Imagerie mise à jour avec succès.');
    }

    public function destroy(ImageMedicale $imagerie)
    {
        $this->authorize('delete', $imagerie);
        
        if ($imagerie->orthanc_id) {
            $this->orthancService->deleteInstance($imagerie->orthanc_id);
        }
        
        $imagerie->delete();

        return redirect()->route('medecin.imagerie.index')
            ->with('success', 'Imagerie supprimée avec succès.');
    }

    public function viewer(ImageMedicale $imagerie)
    {
        $this->authorize('view', $imagerie);
        $viewerUrl = $this->orthancService->getViewerUrl($imagerie->orthanc_id);
        return view('medecin.imagerie.viewer', compact('imagerie', 'viewerUrl'));
    }
} 
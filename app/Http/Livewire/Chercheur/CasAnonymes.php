<?php

namespace App\Http\Livewire\Chercheur;

use Livewire\Component;
use App\Models\Dossiers_Medicaux;
use App\Models\Image_Dicom;
use Illuminate\Support\Facades\Storage;

class CasAnonymes extends Component
{
    public $cases = [];
    public $selectedCaseId;
    public $dicomFiles = [];

    public function mount()
    {
        // Load anonymized cases (assuming a scope or flag for anonymized)
        $this->cases = Dossiers_Medicaux::where('anonymized', true)->get();
    }

    public function loadDicomFiles($caseId)
    {
        $this->selectedCaseId = $caseId;
        $this->dicomFiles = Image_Dicom::where('dossier_id', $caseId)->get();
    }

    public function downloadDicom($fileId)
    {
        $file = Image_Dicom::find($fileId);
        if ($file && Storage::exists($file->filepath)) {
            return response()->download(storage_path('app/' . $file->filepath));
        }
        session()->flash('error', 'Fichier DICOM introuvable.');
    }

    public function render()
    {
        return view('livewire.chercheur.cas-anonymes', [
            'cases' => $this->cases,
            'dicomFiles' => $this->dicomFiles,
            'selectedCaseId' => $this->selectedCaseId,
        ]);
    }
}

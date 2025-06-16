<?php

namespace App\Http\Livewire\Medecin;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Dossiers_Medicaux;
use App\Models\ImageDicom;

class DossierDetails extends Component
{
    public $patientId;
    public $dossier;
    public $dicomImages;

    protected $listeners = ['patientSelected' => 'loadDossier'];

    public function loadDossier($patientId)
    {
        $this->patientId = $patientId;
        $this->dossier = Dossiers_Medicaux::where('patient_id', $patientId)->first();
        $this->dicomImages = ImageDicom::where('patient_id', $patientId)->get();
    }

    public function render()
    {
        return view('livewire.medecin.dossier-details');
    }
}

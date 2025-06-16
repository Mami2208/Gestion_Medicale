<?php

namespace App\Http\Livewire\Infirmier;

use Livewire\Component;
use App\Models\Patient;
use App\Models\historique_medical;
use Illuminate\Support\Facades\Auth;

class Soins extends Component
{
    public $patientId;
    public $historique;
    public $description;
    public $date;

    protected $listeners = ['patientSelected' => 'loadHistorique'];

    public function loadHistorique($patientId)
    {
        $this->patientId = $patientId;
        $this->historique = historique_medical::where('patient_id', $patientId)->get();
    }

    public function addVitalConstant()
    {
        $this->validate([
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        historique_medical::create([
            'description' => $this->description,
            'date' => $this->date,
            'patient_id' => $this->patientId,
        ]);

        $this->description = '';
        $this->date = '';
        $this->loadHistorique($this->patientId);
    }

    public function render()
    {
        return view('livewire.infirmier.soins');
    }
}

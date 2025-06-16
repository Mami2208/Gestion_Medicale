<?php

namespace App\Http\Livewire\Medecin;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Medecin;

class PatientList extends Component
{
    public $specialite;
    public $patients;
    public $specialites;

    public function mount()
    {
        $this->specialites = Medecin::select('specialite')->distinct()->pluck('specialite');
        $this->patients = collect();
    }

    public function updatedSpecialite($value)
    {
        $this->patients = Patient::whereHas('medecin', function($query) use ($value) {
            $query->where('specialite', $value);
        })->get();
    }

    public function render()
    {
        return view('livewire.medecin.patient-list');
    }
}

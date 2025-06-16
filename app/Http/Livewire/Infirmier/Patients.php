<?php

namespace App\Http\Livewire\Infirmier;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Infirmier;
use Illuminate\Support\Facades\Auth;

class Patients extends Component
{
    public $patients;

    public function mount()
    {
        $this->patients = collect();
        $this->loadPatients();
    }

    public function loadPatients()
    {
        // Get the authenticated infirmier
        $infirmier = Infirmier::where('id', Auth::id())->first();

        if ($infirmier && $infirmier->services) {
            // Assuming services is a string, filter patients linked to the infirmier's service
            // Here we assume patients have a 'service' attribute to match, adjust as needed
            $service = $infirmier->services;
            $this->patients = Patient::where('service', $service)->get();
        } else {
            $this->patients = collect();
        }
    }

    public function render()
    {
        return view('livewire.infirmier.patients');
    }
}

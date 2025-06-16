<?php

namespace App\Http\Livewire\Patient;

use Livewire\Component;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class Dossier extends Component
{
    public $patient;

    public function mount()
    {
        $user = Auth::user();
        $this->patient = Patient::where('id', $user->id)->with('dossiersMedicaux')->first();
    }

    public function render()
    {
        return view('livewire.patient.dossier', [
            'dossiers' => $this->patient ? $this->patient->dossiersMedicaux : collect(),
        ]);
    }
}

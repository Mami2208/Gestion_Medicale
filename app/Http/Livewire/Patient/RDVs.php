<?php

namespace App\Http\Livewire\Patient;

use Livewire\Component;
use App\Models\Rendez_Vous;
use Illuminate\Support\Facades\Auth;

class RDVs extends Component
{
    public $rdvs;

    public function mount()
    {
        $user = Auth::user();
        $this->rdvs = Rendez_Vous::where('patient_id', $user->id)->orderBy('date', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.patient.r-dvs', [
            'rdvs' => $this->rdvs,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Patient;

use Livewire\Component;
use App\Models\Image_Dicom;
use Illuminate\Support\Facades\Auth;

class DicomViewer extends Component
{
    public $images;

    public function mount()
    {
        $user = Auth::user();
        $this->images = Image_Dicom::where('patient_id', $user->id)->get();
    }

    public function render()
    {
        return view('livewire.patient.dicom-viewer', [
            'images' => $this->images,
        ]);
    }
}

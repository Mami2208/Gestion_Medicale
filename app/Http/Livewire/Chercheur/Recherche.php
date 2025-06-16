<?php

namespace App\Http\Livewire\Chercheur;

use Livewire\Component;
use App\Models\Dossiers_Medicaux;
use App\Models\Image_Dicom;

class Recherche extends Component
{
    public $searchTerm = '';
    public $results = [];

    public function updatedSearchTerm()
    {
        $term = '%' . $this->searchTerm . '%';
        $this->results = Dossiers_Medicaux::where('description', 'like', $term)
            ->orWhereHas('patient.utilisateur', function ($query) use ($term) {
                $query->where('nom', 'like', $term)
                      ->orWhere('prenom', 'like', $term);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.chercheur.recherche', [
            'results' => $this->results,
        ]);
    }
}

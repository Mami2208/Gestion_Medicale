<?php

namespace App\Http\Livewire\Secretaire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Dossiers_Medicaux;

class CreateDossier extends Component
{
    public $patient_id;
    public $numero_dossier;
    public $description;
    public $poids;
    public $taille;
    public $groupe_sanguin;
    public $allergies = [];
    public $antecedents_medicaux = [];
    public $observations;
    public $traitements_en_cours = [];
    
    // Pour la création d'un nouveau patient si nécessaire
    public $creation_patient = false;
    public $nom;
    public $prenom;
    public $date_naissance;
    public $sexe;
    public $telephone;
    public $email;
    public $adresse;

    protected $rules = [
        'patient_id' => 'required_without:creation_patient|exists:patients,id',
        'numero_dossier' => 'required|string|unique:dossiers_medicaux,numero_dossier',
        'description' => 'required|string',
        'poids' => 'nullable|numeric|min:0|max:500',
        'taille' => 'nullable|numeric|min:0|max:300',
        'groupe_sanguin' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        'observations' => 'nullable|string',
        
        // Règles pour la création d'un nouveau patient
        'nom' => 'required_if:creation_patient,true|string|max:255',
        'prenom' => 'required_if:creation_patient,true|string|max:255',
        'date_naissance' => 'required_if:creation_patient,true|date',
        'sexe' => 'required_if:creation_patient,true|in:M,F,Autre',
        'telephone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'adresse' => 'nullable|string|max:255',
    ];
    
    public function mount()
    {
        // Générer un numéro de dossier unique basé sur la date et un identifiant aléatoire
        $this->numero_dossier = 'DM-' . date('Ymd') . '-' . rand(1000, 9999);
    }
    
    public function addAllergie()
    {
        $this->allergies[] = '';
    }
    
    public function removeAllergie($index)
    {
        unset($this->allergies[$index]);
        $this->allergies = array_values($this->allergies);
    }
    
    public function addAntecedent()
    {
        $this->antecedents_medicaux[] = '';
    }
    
    public function removeAntecedent($index)
    {
        unset($this->antecedents_medicaux[$index]);
        $this->antecedents_medicaux = array_values($this->antecedents_medicaux);
    }
    
    public function addTraitement()
    {
        $this->traitements_en_cours[] = '';
    }
    
    public function removeTraitement($index)
    {
        unset($this->traitements_en_cours[$index]);
        $this->traitements_en_cours = array_values($this->traitements_en_cours);
    }

    public function submit()
    {
        $this->validate();
        
        // Créer un nouveau patient si l'option est sélectionnée
        if ($this->creation_patient) {
            // Créer un utilisateur d'abord
            $utilisateur = \App\Models\Utilisateur::create([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'role' => 'PATIENT',
                'password' => \Illuminate\Support\Facades\Hash::make('password'), // Mot de passe temporaire
            ]);
            
            // Puis créer le patient
            $patient = Patient::create([
                'utilisateur_id' => $utilisateur->id,
                'date_naissance' => $this->date_naissance,
                'sexe' => $this->sexe,
                'adresse' => $this->adresse,
                'groupe_sanguin' => $this->groupe_sanguin,
            ]);
            
            $this->patient_id = $patient->id;
        }

        // Créer le dossier médical avec toutes les informations
        Dossiers_Medicaux::create([
            'patient_id' => $this->patient_id,
            'numero_dossier' => $this->numero_dossier,
            'description' => $this->description,
            'poids' => $this->poids,
            'taille' => $this->taille,
            'groupe_sanguin' => $this->groupe_sanguin,
            'allergies' => $this->allergies,
            'antecedents_medicaux' => $this->antecedents_medicaux,
            'observations' => $this->observations,
            'traitements_en_cours' => $this->traitements_en_cours,
            'date_creation' => now(),
            'statut' => 'actif',
        ]);

        session()->flash('success', 'Dossier médical créé avec succès.');

        // Réinitialiser le formulaire
        $this->reset();
        $this->mount(); // Générer un nouveau numéro de dossier
    }

    public function render()
    {
        $patients = Patient::with('utilisateur')->get();
        return view('livewire.secretaire.create-dossier', compact('patients'));
    }
}

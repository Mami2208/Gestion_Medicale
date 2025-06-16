<?php

namespace App\Http\Livewire\Secretaire;

use Livewire\Component;
use App\Models\Utilisateur;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SecretaireCreated;

class CreatePatient extends Component
{
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:utilisateurs,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function submit()
    {
        $this->validate();

        $patient = Utilisateur::create([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'mot_de_passe' => Hash::make($this->password),
            'role' => 'PATIENT',
        ]);

        // Optionally send email or other post-creation logic

        session()->flash('success', 'Patient créé avec succès.');

        $this->reset(['nom', 'prenom', 'email', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.secretaire.create-patient');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use App\Models\Medecin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateMedecin extends Command
{
    protected $signature = 'medecin:create';
    protected $description = 'Créer un nouveau médecin';

    public function handle()
    {
        DB::beginTransaction();

        try {
            $nom = $this->ask('Quel est le nom du médecin ?');
            $prenom = $this->ask('Quel est le prénom du médecin ?');
            $email = $this->ask('Quel est l\'email du médecin ?');
            $password = $this->secret('Quel est le mot de passe du médecin ?');
            $telephone = $this->ask('Quel est le numéro de téléphone du médecin ? (optionnel)');
            $specialite = $this->ask('Quelle est la spécialité du médecin ?');

            // Créer l'utilisateur
            $utilisateur = Utilisateur::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => Hash::make($password),
                'role' => 'MEDECIN',
                'telephone' => $telephone ?: null,
            ]);

            // Créer le médecin
            $medecin = Medecin::create([
                'utilisateur_id' => $utilisateur->id,
                'matricule' => 'MED-' . str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT),
                'specialite' => $specialite,
            ]);

            DB::commit();

            $this->info('Médecin créé avec succès !');
            $this->table(
                ['Nom', 'Prénom', 'Email', 'Spécialité', 'Matricule'],
                [[$utilisateur->nom, $utilisateur->prenom, $utilisateur->email, $medecin->specialite, $medecin->matricule]]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erreur lors de la création du médecin : ' . $e->getMessage());
        }
    }
} 
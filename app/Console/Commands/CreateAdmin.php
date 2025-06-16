<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Créer un nouvel administrateur';

    public function handle()
    {
        $nom = $this->ask('Quel est le nom de l\'administrateur ?');
        $prenom = $this->ask('Quel est le prénom de l\'administrateur ?');
        $email = $this->ask('Quel est l\'email de l\'administrateur ?');
        $password = $this->secret('Quel est le mot de passe de l\'administrateur ?');
        $telephone = $this->ask('Quel est le numéro de téléphone de l\'administrateur ? (optionnel)');

        try {
            $admin = Utilisateur::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => Hash::make($password),
                'role' => 'ADMIN',
                'telephone' => $telephone ?: null,
            ]);

            $this->info('Administrateur créé avec succès !');
            $this->table(
                ['Nom', 'Prénom', 'Email', 'Rôle'],
                [[$admin->nom, $admin->prenom, $admin->email, $admin->role]]
            );
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'administrateur : ' . $e->getMessage());
        }
    }
} 
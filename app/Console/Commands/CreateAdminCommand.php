<?php

namespace App\Console\Commands;

use App\Models\Utilisateur;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Créer un compte administrateur';

    public function handle()
    {
        $this->info('Création d\'un compte administrateur');
        $this->info('--------------------------------');

        // Demander les informations
        $nom = $this->ask('Nom de l\'administrateur');
        $prenom = $this->ask('Prénom de l\'administrateur');
        $email = $this->ask('Email de l\'administrateur');
        $password = $this->secret('Mot de passe de l\'administrateur');
        $password_confirmation = $this->secret('Confirmer le mot de passe');
        $telephone = $this->ask('Numéro de téléphone (optionnel)');

        // Validation des données
        $validator = Validator::make([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'telephone' => $telephone,
        ], [
            'nom' => 'required|min:2|max:50',
            'prenom' => 'required|min:2|max:50',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:8|confirmed',
            'telephone' => 'nullable|regex:/^[0-9]{9,10}$/',
        ], [
            'telephone.regex' => 'Le numéro de téléphone doit contenir 9 ou 10 chiffres.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Confirmation
        if (!$this->confirm('Voulez-vous créer cet administrateur avec ces informations ?')) {
            $this->info('Création annulée.');
            return 0;
        }

        try {
            $admin = Utilisateur::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => Hash::make($password),
                'role' => 'ADMIN',
                'telephone' => $telephone ?: '0000000000',
                'statut' => 'ACTIF'
            ]);

            $this->info('Administrateur créé avec succès !');
            $this->table(
                ['Email', 'Nom', 'Prénom', 'Rôle', 'Statut'],
                [[$admin->email, $admin->nom, $admin->prenom, $admin->role, $admin->statut]]
            );

            $this->info('Vous pouvez maintenant vous connecter avec :');
            $this->info('Email : ' . $email);
            $this->info('Mot de passe : ' . $password);

        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'administrateur : ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 
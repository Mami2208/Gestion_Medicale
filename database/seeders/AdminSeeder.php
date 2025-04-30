<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@gmail.com',
            'mot_de_passe' => Hash::make('Admin123!'), // Changer ce mot de passe
            'role' => 'ADMIN',
            'telephone' => '+1234567890',
            'dateNaiss' => '1990-01-01'
        ]);
    }
}
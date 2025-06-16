<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si l'administrateur existe déjà
        if (DB::table('utilisateurs')->where('email', 'admin@example.com')->doesntExist()) {
            DB::table('utilisateurs')->insert([
                'nom' => 'Admin',
                'prenom' => 'Admin',
                'email' => 'admin@example.com',
                'mot_de_passe' => Hash::make('Admin@123'), // Mot de passe fort par défaut
                'role' => 'ADMIN',
                'telephone' => '0000000000',
                'adresse' => 'Adresse admin',
                'code_postal' => '00000',
                'ville' => 'Ville',
                'date_naissance' => '1990-01-01',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Administrateur créé avec succès !');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Mot de passe: Admin@123');
        } else {
            $this->command->info('Un administrateur avec cet email existe déjà.');
        }
    }
}

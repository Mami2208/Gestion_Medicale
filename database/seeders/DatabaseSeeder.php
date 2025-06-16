<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DossiersMedicauxSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            MedicamentSeeder::class,
            // Autres seeders...
            
            // Seeders pour les données de test
            DossiersMedicauxSeeder::class,
        ]);
    }
}

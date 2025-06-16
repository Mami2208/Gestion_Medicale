<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dossiers_Medicaux;
use App\Models\Patient;
use App\Models\Medecin;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DossiersMedicauxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Désactiver les contraintes de clé étrangère temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Vider la table avant de la remplir
        Dossiers_Medicaux::truncate();
        
        $faker = Faker::create('fr_FR');
        $groupesSanguins = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $statuts = ['actif', 'archive', 'en_cours'];
        
        // Récupérer tous les patients et médecins existants
        $patients = Patient::all();
        $medecins = Medecin::all();
        
        if ($patients->isEmpty() || $medecins->isEmpty()) {
            $this->command->info('Aucun patient ou médecin trouvé. Veuillez d\'abord exécuter les seeders pour les patients et les médecins.');
            return;
        }
        
        // Créer un dossier médical pour chaque patient
        foreach ($patients as $patient) {
            // Choisir un médecin aléatoire
            $medecin = $medecins->random();
            
            // Créer le dossier médical
            Dossiers_Medicaux::create([
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'numero_dossier' => 'DOSS-' . strtoupper(uniqid()),
                'date_creation' => $faker->dateTimeBetween('-5 years', 'now'),
                'observations' => $faker->optional(0.7)->paragraphs(3, true),
                'antecedents' => $faker->optional(0.8)->paragraphs(2, true),
                'antecedents_medicaux' => $faker->optional(0.7)->paragraphs(2, true),
                'allergies' => $faker->optional(0.5)->randomElements(
                    ['pollen', 'acariens', 'arachides', 'pénicilline', 'aspirine', 'latex', 'œufs', 'crustacés'],
                    $faker->numberBetween(0, 3)
                ),
                'groupe_sanguin' => $faker->optional(0.8)->randomElement($groupesSanguins),
                'taille' => $faker->optional(0.9)->randomFloat(2, 140, 200),
                'poids' => $faker->optional(0.9)->randomFloat(1, 40, 150),
                'statut' => $faker->randomElement($statuts),
                'motif_consultation' => $faker->optional(0.7)->sentence(),
                'traitements_en_cours' => $faker->optional(0.6)->paragraph(),
                'created_at' => $faker->dateTimeBetween('-5 years', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
        
        // Réactiver les contraintes de clé étrangère
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Dossiers médicaux créés avec succès !');
    }
}

<?php

namespace Database\Factories;

use App\Models\Dossiers_Medicaux;
use App\Models\Patient;
use App\Models\Medecin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DossiersMedicauxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dossiers_Medicaux::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Récupérer un patient existant ou en créer un nouveau
        $patient = Patient::inRandomOrder()->first() ?? \App\Models\Patient::factory()->create();
        
        // Récupérer un médecin existant ou en créer un nouveau
        $medecin = Medecin::inRandomOrder()->first() ?? \App\Models\Medecin::factory()->create();
        
        $groupesSanguins = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $statuts = ['actif', 'archive', 'en_cours'];
        
        return [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'numero_dossier' => 'DOSS-' . strtoupper(Str::random(8)),
            'date_creation' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'observations' => $this->faker->optional(0.7)->paragraphs(3, true),
            'antecedents' => $this->faker->optional(0.8)->paragraphs(2, true),
            'antecedents_medicaux' => $this->faker->optional(0.7)->paragraphs(2, true),
            'allergies' => $this->faker->optional(0.5)->randomElements(
                ['pollen', 'acariens', 'arachides', 'pénicilline', 'aspirine', 'latex', 'œufs', 'crustacés'],
                $this->faker->numberBetween(0, 3)
            ),
            'groupe_sanguin' => $this->faker->optional(0.8)->randomElement($groupesSanguins),
            'taille' => $this->faker->optional(0.9)->randomFloat(2, 140, 200),
            'poids' => $this->faker->optional(0.9)->randomFloat(1, 40, 150),
            'statut' => $this->faker->randomElement($statuts),
            'motif_consultation' => $this->faker->optional(0.7)->sentence(),
            'traitements_en_cours' => $this->faker->optional(0.6)->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

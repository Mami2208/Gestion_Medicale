<?php

namespace Database\Seeders;

use App\Models\Medicament;
use Illuminate\Database\Seeder;

class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicaments = [
            [
                'nom' => 'Paracétamol',
                'forme_pharmaceutique' => 'Comprimé',
                'voie_administration' => 'Orale',
                'dose' => '1000',
                'unite_mesure' => 'mg',
                'code_cip' => '3400931343758',
                'description' => 'Antidouleur et antipyrétique',
                'sur_ordonnance' => false,
                'est_actif' => true
            ],
            [
                'nom' => 'Ibuprofène',
                'forme_pharmaceutique' => 'Comprimé',
                'voie_administration' => 'Orale',
                'dose' => '400',
                'unite_mesure' => 'mg',
                'code_cip' => '3400931343765',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'sur_ordonnance' => false,
                'est_actif' => true
            ],
            [
                'nom' => 'Amoxicilline',
                'forme_pharmaceutique' => 'Gélule',
                'voie_administration' => 'Orale',
                'dose' => '500',
                'unite_mesure' => 'mg',
                'code_cip' => '3400931343772',
                'description' => 'Antibiotique de la famille des pénicillines',
                'sur_ordonnance' => true,
                'est_actif' => true
            ],
            [
                'nom' => 'Oméprazole',
                'forme_pharmaceutique' => 'Gélule gastro-résistante',
                'voie_administration' => 'Orale',
                'dose' => '20',
                'unite_mesure' => 'mg',
                'code_cip' => '3400931343789',
                'description' => 'Inhibiteur de la pompe à protons',
                'sur_ordonnance' => true,
                'est_actif' => true
            ],
            [
                'nom' => 'Loratadine',
                'forme_pharmaceutique' => 'Comprimé',
                'voie_administration' => 'Orale',
                'dose' => '10',
                'unite_mesure' => 'mg',
                'code_cip' => '3400931343796',
                'description' => 'Antihistaminique H1 de deuxième génération',
                'sur_ordonnance' => false,
                'est_actif' => true
            ]
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicament;

class MedicamentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medicaments = [
            [
                'nom' => 'Doliprane',
                'code' => 'DOL1000',
                'description' => 'Antalgique et antipyrétique à base de paracétamol',
                'categorie' => 'ANTALGIQUE',
                'forme' => 'COMPRIME',
                'dosage' => '1000mg',
                'contre_indications' => 'Insuffisance hépatique sévère, allergie au paracétamol',
                'effets_secondaires' => 'Rares cas d\'allergie',
                'disponible' => true
            ],
            [
                'nom' => 'Ibuprofene',
                'code' => 'IBU400',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'categorie' => 'ANTI_INFLAMMATOIRE',
                'forme' => 'COMPRIME',
                'dosage' => '400mg',
                'contre_indications' => 'Ulcère gastroduodénal, insuffisance rénale sévère, grossesse à partir du 6ème mois',
                'effets_secondaires' => 'Brûlures d\'estomac, nausées',
                'disponible' => true
            ],
            [
                'nom' => 'Amoxicilline',
                'code' => 'AMOX1G',
                'description' => 'Antibiotique de la famille des pénicillines',
                'categorie' => 'ANTIBIOTIQUE',
                'forme' => 'GELULE',
                'dosage' => '1g',
                'contre_indications' => 'Allergie aux pénicillines, mononucléose infectieuse',
                'effets_secondaires' => 'Troubles digestifs, réactions allergiques',
                'disponible' => true
            ],
            [
                'nom' => 'Ventoline',
                'code' => 'VENT100',
                'description' => 'Bronchodilatateur pour le traitement de l\'asthme',
                'categorie' => 'BRONCHODILATATEUR',
                'forme' => 'AEROSOL',
                'dosage' => '100mcg/dose',
                'contre_indications' => 'Tachycardie, hypersensibilité au salbutamol',
                'effets_secondaires' => 'Tremblements, tachycardie, maux de tête',
                'disponible' => true
            ],
            [
                'nom' => 'Levothyrox',
                'code' => 'LEVO75',
                'description' => 'Traitement substitutif des hypothyroïdies',
                'categorie' => 'HORMONE_THYROIDIENNE',
                'forme' => 'COMPRIME',
                'dosage' => '75mcg',
                'contre_indications' => 'Hyperthyroïdie non traitée, infarctus du myocarde récent',
                'effets_secondaires' => 'Signes d\'hyperthyroïdie en cas de surdosage',
                'disponible' => true
            ]
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Medicament;

class ListMedicaments extends Command
{
    protected $signature = 'medicaments:list';
    protected $description = 'Lister tous les médicaments dans la base de données';

    public function handle()
    {
        $medicaments = Medicament::all();

        if ($medicaments->isEmpty()) {
            $this->info("Aucun médicament trouvé dans la base de données.");
            return;
        }

        $this->info("Liste des médicaments :\n");

        $headers = ['ID', 'Nom', 'Dosage', 'Forme', 'Ordonnance', 'Actif'];
        $rows = [];

        foreach ($medicaments as $med) {
            $rows[] = [
                $med->id,
                $med->nom,
                $med->dose . $med->unite_mesure,
                $med->forme_pharmaceutique,
                $med->sur_ordonnance ? 'Oui' : 'Non',
                $med->est_actif ? 'Oui' : 'Non'
            ];
        }

        $this->table($headers, $rows);
    }
}

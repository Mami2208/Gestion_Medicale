<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckInfirmiersTable extends Command
{
    protected $signature = 'check:infirmiers-table';
    protected $description = 'Vérifie la structure de la table infirmiers';

    public function handle()
    {
        if (!Schema::hasTable('infirmiers')) {
            $this->error("La table 'infirmiers' n'existe pas.");
            return 1;
        }

        $this->info("Colonnes de la table 'infirmiers':");
        $columns = Schema::getColumnListing('infirmiers');
        $this->table(['Colonne'], array_map(fn($col) => [$col], $columns));

        $this->info("\nDétails des colonnes de la table 'infirmiers':");
        $columns = DB::select('SHOW COLUMNS FROM infirmiers');
        $this->table(
            ['Champ', 'Type', 'Null', 'Clé', 'Défaut', 'Extra'],
            array_map(fn($col) => (array) $col, $columns)
        );

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckSecretaireTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:secretaire-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie la structure de la table secretaire_medicals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('secretaire_medicals')) {
            $this->error("La table 'secretaire_medicals' n'existe pas.");
            return 1;
        }

        $this->info("Colonnes de la table 'secretaire_medicals':");
        $columns = Schema::getColumnListing('secretaire_medicals');
        $this->table(['Colonne'], array_map(fn($col) => [$col], $columns));

        $this->info("\nDétails des colonnes de la table 'secretaire_medicals':");
        $columns = DB::select('SHOW COLUMNS FROM secretaire_medicals');
        $this->table(
            ['Champ', 'Type', 'Null', 'Clé', 'Défaut', 'Extra'],
            array_map(fn($col) => (array) $col, $columns)
        );

        return 0;
    }
}

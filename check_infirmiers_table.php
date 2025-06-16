<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware_Contracts_ConsoleKernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Vérifier si la table existe
if (!Schema::hasTable('infirmiers')) {
    echo "La table 'infirmiers' n'existe pas.\n";
    exit(1);
}

// Afficher les colonnes de la table
echo "Colonnes de la table 'infirmiers':\n";
$columns = Schema::getColumnListing('infirmiers');
print_r($columns);

// Afficher les détails des colonnes
echo "\nDétails des colonnes de la table 'infirmiers':\n";
$columns = DB::select('SHOW COLUMNS FROM infirmiers');
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";}

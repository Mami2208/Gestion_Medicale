<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Vérification de la connexion à la base de données...\n";
    
    // Afficher le nom de la base de données
    $database = DB::connection()->getDatabaseName();
    echo "Base de données connectée : $database\n";
    
    // Vérifier si la table existe
    if (DB::getSchemaBuilder()->hasTable('traitements')) {
        echo "La table 'traitements' existe.\n";
        
        // Afficher les colonnes
        $columns = DB::getSchemaBuilder()->getColumnListing('traitements');
        echo "Colonnes de la table 'traitements' : \n";
        foreach ($columns as $column) {
            $type = DB::getSchemaBuilder()->getColumnType('traitements', $column);
            echo "- $column ($type)\n";
        }
    } else {
        echo "La table 'traitements' n'existe pas.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}

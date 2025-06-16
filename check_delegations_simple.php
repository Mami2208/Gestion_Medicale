<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Afficher le contenu de la table
    $delegations = DB::table('delegations_acces')->get();
    
    echo "Nombre de délégations: " . $delegations->count() . "\n\n";
    
    if ($delegations->count() > 0) {
        echo "Détails des délégations :\n";
        echo str_repeat("-", 100) . "\n";
        
        // Afficher les en-têtes
        $first = $delegations->first();
        $headers = array_keys((array) $first);
        echo implode("\t|", $headers) . "\n";
        echo str_repeat("-", 100) . "\n";
        
        // Afficher les données
        foreach ($delegations as $delegation) {
            echo implode("\t|", array_map(function($field) use ($delegation) {
                return $delegation->$field ?? 'NULL';
            }, $headers)) . "\n";
        }
    } else {
        echo "Aucune délégation trouvée.\n";
    }
    
} catch (\Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

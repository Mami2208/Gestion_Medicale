<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illine\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Vérifier la connexion à la base de données
    DB::connection()->getPdo();
    echo "Connexion à la base de données réussie.\n";
    
    // Afficher le nom de la base de données
    echo "Base de données: " . DB::connection()->getDatabaseName() . "\n\n";
    
    // Vérifier si la table existe
    if (!Schema::hasTable('delegations_acces')) {
        die("La table 'delegations_acces' n'existe pas.\n");
    }
    
    // Afficher la structure de la table
    echo "Structure de la table 'delegations_acces':\n";
    $columns = DB::select('DESCRIBE delegations_acces');
    print_r($columns);
    
    // Compter le nombre d'entrées
    $count = DB::table('delegations_acces')->count();
    echo "\nNombre de délégations: $count\n";
    
    // Afficher les 10 premières entrées
    if ($count > 0) {
        echo "\nDélégations (max 10):\n";
        $delegations = DB::table('delegations_acces')
            ->select('*')
            ->limit(10)
            ->get();
            
        foreach ($delegations as $delegation) {
            print_r(json_decode(json_encode($delegation), true));
            echo "\n";
        }
    }
    
} catch (\Exception $e) {
    die("Erreur: " . $e->getMessage() . "\n");
}

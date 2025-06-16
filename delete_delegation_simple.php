<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Supprimer la délégation avec l'ID 2
$deleted = DB::table('delegations_acces')->where('id', 2)->delete();

if ($deleted) {
    echo "Délégation #2 supprimée avec succès.\n";
} else {
    echo "Aucune délégation trouvée avec l'ID 2.\n";
}

// Vérifier les délégations restantes
echo "\nDélégations restantes :\n";
$delegations = DB::table('delegations_acces')->get(['id', 'medecin_id', 'infirmier_id', 'statut']);

foreach ($delegations as $d) {
    echo "- ID: {$d->id}, Médecin: {$d->medecin_id}, Infirmier: {$d->infirmier_id}, Statut: {$d->statut}\n";
}

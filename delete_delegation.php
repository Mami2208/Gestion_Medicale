<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illine\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Supprimer la délégation avec l'ID 2
$deleted = DB::table('delegations_acces')->where('id', 2)->delete();

if ($deleted) {
    echo "Délégation #2 supprimée avec succès.\n";
} else {
    echo "Aucune délégation trouvée avec l'ID 2.\n";}

// Vérifier les délégations restantes
$delegations = DB::table('delegations_acces')->get();

echo "\nDélégations restantes :\n";
foreach ($delegations as $delegation) {
    echo "- ID: {$delegation->id}, Médecin: {$delegation->medecin_id}, Infirmier: {$delegation->infirmier_id}, Statut: {$delegation->statut}\n";
}

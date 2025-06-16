<?php

require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Medicament;

$meds = Medicament::all();

echo "Médicaments dans la base de données :\n\n";

if ($meds->count() > 0) {
    foreach ($meds as $med) {
        echo "- ID: {$med->id}\n";
        echo "  Nom: {$med->nom}\n";
        echo "  Forme: {$med->forme_pharmaceutique}\n";
        echo "  Dosage: {$med->dose}{$med->unite_mesure}\n\n";
    }
} else {
    echo "Aucun médicament trouvé dans la base de données.\n";
}

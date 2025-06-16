<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Medicament;

$medicaments = Medicament::all();

echo "Médicaments dans la base de données :\n";
foreach ($medicaments as $med) {
    echo "- {$med->id}: {$med->nom} ({$med->dose}{$med->unite_mesure})\n";
}

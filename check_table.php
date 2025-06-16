<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illwarenterfaces\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$columns = \DB::select('SHOW COLUMNS FROM traitements');

echo "Structure de la table traitements :\n";
foreach ($columns as $column) {
    echo "- {$column->Field} : {$column->Type}\n";
}

<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Vérifier si la table existe
if (!Schema::hasTable('examens')) {
    echo "La table 'examens' n'existe pas.\n";
    exit(1);
}

// Afficher les colonnes de la table
echo "Colonnes de la table 'examens':\n";
$columns = Schema::getColumnListing('examens');
print_r($columns);

// Afficher les détails des colonnes
echo "\nDétails des colonnes de la table 'examens':\n";
$columns = DB::select('SHOW COLUMNS FROM examens');
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
    if ($column->Key === 'PRI') echo "  - Clé primaire\n";
    if ($column->Key === 'MUL') echo "  - Clé étrangère\n";
    if ($column->Null === 'NO') echo "  - Non null\n";
    if ($column->Default !== null) echo "  - Valeur par défaut: {$column->Default}\n";
    if ($column->Extra !== '') echo "  - Extra: {$column->Extra}\n";
}

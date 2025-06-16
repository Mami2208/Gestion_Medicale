<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Vérification de la table 'traitements' ===\n";
    
    // Vérifier si la table existe
    if (!DB::getSchemaBuilder()->hasTable('traitements')) {
        die("La table 'traitements' n'existe pas.\n");
    }
    
    // Afficher les colonnes avec leurs détails
    $columns = DB::select("SHOW COLUMNS FROM traitements");
    
    echo "\n=== Colonnes de la table 'traitements' ===\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} : {$column->Type}";
        echo $column->Null === 'YES' ? ' NULL' : ' NOT NULL';
        echo $column->Key ? " [{$column->Key}]" : "";
        echo $column->Default !== null ? " DEFAULT '{$column->Default}'
" : "\n";
    }
    
    // Afficher les clés étrangères
    $foreigns = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
                          WHERE TABLE_SCHEMA = DATABASE() 
                          AND TABLE_NAME = 'traitements' 
                          AND REFERENCED_TABLE_NAME IS NOT NULL");
    
    if (!empty($foreigns)) {
        echo "\n=== Clés étrangères ===\n";
        foreach ($foreigns as $foreign) {
            echo "- {$foreign->COLUMN_NAME} -> {$foreign->REFERENCED_TABLE_NAME}({$foreign->REFERENCED_COLUMN_NAME})\n";
        }
    }
    
} catch (Exception $e) {
    echo "\nErreur : " . $e->getMessage() . "\n";
}

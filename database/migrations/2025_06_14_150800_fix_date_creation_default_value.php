<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Modifier la colonne date_creation pour utiliser CURRENT_TIMESTAMP comme valeur par défaut
        DB::statement("ALTER TABLE dossiers_medicaux MODIFY COLUMN date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        // Revenir à la définition précédente si nécessaire
        DB::statement("ALTER TABLE dossiers_medicaux MODIFY COLUMN date_creation DATETIME DEFAULT CURRENT_TIMESTAMP");
    }
};

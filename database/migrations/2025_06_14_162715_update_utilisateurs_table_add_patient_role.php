<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne role pour inclure 'PATIENT' comme valeur possible
        \DB::statement("ALTER TABLE utilisateurs MODIFY COLUMN role ENUM('ADMIN','MEDECIN','INFIRMIER','SECRETAIRE','PATIENT') NOT NULL DEFAULT 'PATIENT'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à la définition précédente de la colonne role
        \DB::statement("ALTER TABLE utilisateurs MODIFY COLUMN role ENUM('ADMIN','MEDECIN','INFIRMIER','SECRETAIRE') NOT NULL DEFAULT 'ADMIN'");
    }
};

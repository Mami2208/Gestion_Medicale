<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = 'dossiers';
        
        // Ajout de l'index composite patient_id + medecin_id
        // On utilise une instruction SQL brute pour éviter les problèmes de vérification d'existence
        DB::statement('CREATE INDEX IF NOT EXISTS dossiers_patient_id_medecin_id_index ON ' . $tableName . ' (patient_id, medecin_id)');
        
        // Ajout d'index simples sur les colonnes fréquemment utilisées
        $indexes = [
            'created_at' => 'dossiers_created_at_index',
            'patient_id' => 'dossiers_patient_id_index',
            'medecin_id' => 'dossiers_medecin_id_index',
            'numero_dossier' => 'dossiers_numero_dossier_index',
            'numero_securite_sociale' => 'dossiers_numero_securite_sociale_index',
        ];
        
        foreach ($indexes as $column => $indexName) {
            if (Schema::hasColumn($tableName, $column)) {
                DB::statement('CREATE INDEX IF NOT EXISTS ' . $indexName . ' ON ' . $tableName . ' (' . $column . ')');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = 'dossiers';
        
        // Suppression des index créés
        $indexesToDrop = [
            'dossiers_patient_id_medecin_id_index',
            'dossiers_created_at_index',
            'dossiers_patient_id_index',
            'dossiers_medecin_id_index',
            'dossiers_numero_dossier_index',
            'dossiers_numero_securite_sociale_index',
        ];
        
        foreach ($indexesToDrop as $indexName) {
            // On utilise une instruction SQL brute avec une vérification d'existence
            DB::statement('DROP INDEX IF EXISTS ' . $indexName . ' ON ' . $tableName);
        }
    }
};

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
        // Supprimer les contraintes de clé étrangère existantes
        Schema::table('delegations_acces', function (Blueprint $table) {
            // Vérifier si la contrainte existe avant de la supprimer
            if (Schema::hasColumn('delegations_acces', 'medecin_id')) {
                $table->dropForeign(['medecin_id']);
            }
            
            if (Schema::hasColumn('delegations_acces', 'infirmier_id')) {
                $table->dropForeign(['infirmier_id']);
            }
            
            if (Schema::hasColumn('delegations_acces', 'patient_id')) {
                $table->dropForeign(['patient_id']);
            }
        });
        
        // Modifier la structure de la table
        Schema::table('delegations_acces', function (Blueprint $table) {
            // Changer le type de medecin_id pour qu'il référence utilisateurs.id
            $table->unsignedBigInteger('medecin_id')->change();
            $table->unsignedBigInteger('infirmier_id')->change();
            $table->unsignedBigInteger('patient_id')->change();
            
            // Recréer les contraintes de clé étrangère avec les bonnes références
            $table->foreign('medecin_id')
                  ->references('id')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->foreign('infirmier_id')
                  ->references('id')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne rien faire ici car nous ne pouvons pas annuler cette migration de manière fiable
    }
};

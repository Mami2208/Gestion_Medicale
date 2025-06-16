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
        // Supprimer la table si elle existe
        Schema::dropIfExists('delegations_acces');
        
        // Recréer la table avec la bonne structure
        Schema::create('delegations_acces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medecin_id');
            $table->unsignedBigInteger('infirmier_id');
            $table->unsignedBigInteger('patient_id');
            $table->enum('statut', ['active', 'inactive'])->default('active');
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();
            $table->string('raison')->nullable();
            $table->timestamps();

            // Clés étrangères
            $table->foreign('medecin_id')
                  ->references('id')
                  ->on('medecins')
                  ->onDelete('cascade');
                  
            $table->foreign('infirmier_id')
                  ->references('id')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
                  
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
                  
            // Index pour améliorer les performances
            $table->index(['medecin_id', 'infirmier_id', 'patient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegations_acces');
    }
};

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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('medecin_id');
            $table->string('numero_dossier')->unique();
            $table->text('antecedents_medicaux')->nullable();
            $table->text('allergies')->nullable();
            $table->text('observations')->nullable();
            $table->enum('statut', ['ACTIF', 'ARCHIVE', 'FERME'])->default('ACTIF');
            $table->timestamp('date_creation')->nullable();
            $table->timestamp('date_derniere_modification')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');

            $table->foreign('medecin_id')
                  ->references('id')
                  ->on('medecins')
                  ->onDelete('cascade');

            // Index pour optimiser les recherches
            $table->index(['patient_id', 'medecin_id']);
            $table->index('numero_dossier');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};

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
        if (!Schema::hasTable('traitements')) {
            Schema::create('traitements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('patient_id');
                $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
                $table->foreignId('medecin_id')->nullable()->constrained('medecins')->onDelete('set null');

                $table->string('type_traitement'); // 'MEDICAMENT', 'THERAPIE', 'CHIRURGIE', etc.
                $table->text('description');
                $table->date('date_debut');
                $table->date('date_fin')->nullable();
                $table->enum('statut', ['PRESCRIT', 'EN_COURS', 'TERMINE', 'SUSPENDU'])->default('PRESCRIT');
                $table->text('observations')->nullable();
                $table->timestamps();

                $table->index(['patient_id', 'type_traitement']);
                $table->index('statut');
            });
        } else {
            // Si la table existe déjà, vérifier et ajouter les colonnes manquantes
            if (!Schema::hasColumn('traitements', 'patient_id')) {
                Schema::table('traitements', function (Blueprint $table) {
                    $table->unsignedBigInteger('patient_id')->after('id');
                    $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traitements');
    }
};

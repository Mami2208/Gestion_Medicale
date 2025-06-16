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
        Schema::table('examens', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('examens', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->nullable()->after('id');
                $table->foreign('patient_id')
                    ->references('id')
                    ->on('patients')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('examens', 'medecin_id')) {
                $table->unsignedBigInteger('medecin_id')->nullable()->after('patient_id');
                $table->foreign('medecin_id')
                    ->references('id')
                    ->on('medecins')
                    ->onDelete('set null');
            }

            if (!Schema::hasColumn('examens', 'dossiers_medicaux_id')) {
                $table->unsignedBigInteger('dossiers_medicaux_id')->nullable()->after('medecin_id');
                $table->foreign('dossiers_medicaux_id')
                    ->references('id')
                    ->on('dossiers_medicaux')
                    ->onDelete('cascade');
            }

            // Mettre à jour les colonnes existantes pour correspondre au modèle
            if (Schema::hasColumn('examens', 'typeExamen')) {
                $table->renameColumn('typeExamen', 'type_examen');
            }

            if (!Schema::hasColumn('examens', 'description')) {
                $table->text('description')->nullable()->after('type_examen');
            }

            if (!Schema::hasColumn('examens', 'conclusion')) {
                $table->text('conclusion')->nullable()->after('description');
            }

            if (Schema::hasColumn('examens', 'date') && !Schema::hasColumn('examens', 'date_examen')) {
                $table->renameColumn('date', 'date_examen');
            } elseif (!Schema::hasColumn('examens', 'date_examen')) {
                $table->dateTime('date_examen')->nullable()->after('conclusion');
            }

            if (!Schema::hasColumn('examens', 'statut')) {
                $table->enum('statut', ['EN_ATTENTE', 'EN_COURS', 'TERMINE', 'ANNULE'])->default('EN_ATTENTE')->after('date_examen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            // Supprimer les clés étrangères
            if (Schema::hasColumn('examens', 'patient_id')) {
                $table->dropForeign(['patient_id']);
                $table->dropColumn('patient_id');
            }

            if (Schema::hasColumn('examens', 'medecin_id')) {
                $table->dropForeign(['medecin_id']);
                $table->dropColumn('medecin_id');
            }

            if (Schema::hasColumn('examens', 'dossiers_medicaux_id')) {
                $table->dropForeign(['dossiers_medicaux_id']);
                $table->dropColumn('dossiers_medicaux_id');
            }

            // Ne pas annuler les autres modifications pour éviter les pertes de données
        });
    }
};

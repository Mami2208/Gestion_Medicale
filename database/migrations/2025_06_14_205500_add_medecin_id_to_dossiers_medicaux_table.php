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
        Schema::table('dossiers_medicaux', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas déjà
            if (!Schema::hasColumn('dossiers_medicaux', 'medecin_id')) {
                $table->unsignedBigInteger('medecin_id')->nullable()->after('patient_id');
                $table->foreign('medecin_id')
                    ->references('id')
                    ->on('medecins')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers_medicaux', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            if (Schema::hasColumn('dossiers_medicaux', 'medecin_id')) {
                $table->dropForeign(['medecin_id']);
                $table->dropColumn('medecin_id');
            }
        });
    }
};

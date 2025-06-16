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
        Schema::table('consultations', function (Blueprint $table) {
            $table->text('symptomes')->nullable()->after('motif');
            $table->text('traitement')->nullable()->after('diagnostic');
            $table->text('observations')->nullable()->after('observation');
            $table->boolean('paye')->default(false)->after('montant');
            $table->dropColumn('observation'); // Supprimer l'ancienne colonne observation
            $table->dropColumn('type'); // Supprimer la colonne type qui n'est plus utilisée
            $table->string('statut')->default('PLANIFIE')->change(); // Modifier la valeur par défaut
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn(['symptomes', 'traitement', 'observations', 'paye']);
            $table->string('observation')->nullable();
            $table->string('type')->default('premiere_visite');
            $table->string('statut')->default('en_cours')->change();
        });
    }
}; 
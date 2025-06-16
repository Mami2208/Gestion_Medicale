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
            $table->text('antecedents_familiaux')->nullable()->after('antecedents_medicaux');
            $table->text('habitudes_vie')->nullable()->after('antecedents_familiaux');
            $table->text('vaccinations')->nullable()->after('habitudes_vie');
            $table->json('medicaments_chroniques')->nullable()->after('vaccinations');
            $table->string('medecin_traitant')->nullable()->after('medicaments_chroniques');
            $table->string('numero_secu')->nullable()->after('medecin_traitant');
            $table->string('assurance_maladie')->nullable()->after('numero_secu');
            $table->text('notes_medicales')->nullable()->after('assurance_maladie');
            $table->date('date_derniere_maj')->nullable()->after('notes_medicales');
            $table->string('derniere_maj_par')->nullable()->after('date_derniere_maj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers_medicaux', function (Blueprint $table) {
            $table->dropColumn([
                'antecedents_familiaux',
                'habitudes_vie',
                'vaccinations',
                'medicaments_chroniques',
                'medecin_traitant',
                'numero_secu',
                'assurance_maladie',
                'notes_medicales',
                'date_derniere_maj',
                'derniere_maj_par'
            ]);
        });
    }
}; 
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
            $table->text('traitement_prescrit')->nullable()->after('diagnostic');
            $table->text('recommandations')->nullable()->after('traitement_prescrit');
            $table->string('mode_paiement')->nullable()->after('montant');
            $table->boolean('est_paye')->default(false)->after('mode_paiement');
            $table->string('numero_facture')->nullable()->after('est_paye');
            $table->text('notes_medecin')->nullable()->after('statut');
            $table->json('examens_demandes')->nullable()->after('notes_medecin');
            $table->string('prochaine_visite')->nullable()->after('examens_demandes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'traitement_prescrit',
                'recommandations',
                'mode_paiement',
                'est_paye',
                'numero_facture',
                'notes_medecin',
                'examens_demandes',
                'prochaine_visite'
            ]);
        });
    }
}; 
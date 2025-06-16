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
        Schema::table('traitements', function (Blueprint $table) {
            $table->string('posologie')->nullable()->after('description');
            $table->string('frequence')->nullable()->after('posologie');
            $table->string('duree_traitement')->nullable()->after('frequence');
            $table->text('effets_secondaires')->nullable()->after('duree_traitement');
            $table->text('contre_indications')->nullable()->after('effets_secondaires');
            $table->text('instructions_speciales')->nullable()->after('contre_indications');
            $table->string('medicament_principal')->nullable()->after('instructions_speciales');
            $table->json('medicaments_associes')->nullable()->after('medicament_principal');
            $table->string('lieu_traitement')->nullable()->after('medicaments_associes');
            $table->string('personne_responsable')->nullable()->after('lieu_traitement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traitements', function (Blueprint $table) {
            $table->dropColumn([
                'posologie',
                'frequence',
                'duree_traitement',
                'effets_secondaires',
                'contre_indications',
                'instructions_speciales',
                'medicament_principal',
                'medicaments_associes',
                'lieu_traitement',
                'personne_responsable'
            ]);
        });
    }
}; 
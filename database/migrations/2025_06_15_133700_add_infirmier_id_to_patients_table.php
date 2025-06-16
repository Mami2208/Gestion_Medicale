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
        Schema::table('patients', function (Blueprint $table) {
            // Ajouter la colonne infirmier_id comme clé étrangère nullable
            $table->unsignedBigInteger('infirmier_id')->nullable()->after('utilisateur_id');
            
            // Ajouter la clé étrangère
            $table->foreign('infirmier_id')
                  ->references('id')
                  ->on('infirmiers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Supprimer la clé étrangère d'abord
            $table->dropForeign(['infirmier_id']);
            
            // Puis supprimer la colonne
            $table->dropColumn('infirmier_id');
        });
    }
};

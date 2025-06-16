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
        Schema::table('secretaire_medicals', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas déjà
            if (!Schema::hasColumn('secretaire_medicals', 'utilisateur_id')) {
                $table->unsignedBigInteger('utilisateur_id')->after('id');
                $table->foreign('utilisateur_id')
                    ->references('id')
                    ->on('utilisateurs')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secretaire_medicals', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            if (Schema::hasColumn('secretaire_medicals', 'utilisateur_id')) {
                $table->dropForeign(['utilisateur_id']);
                $table->dropColumn('utilisateur_id');
            }
        });
    }
};

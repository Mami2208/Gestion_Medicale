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
        Schema::table('infirmiers', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas déjà
            if (!Schema::hasColumn('infirmiers', 'utilisateur_id')) {
                $table->unsignedBigInteger('utilisateur_id')->after('id');
                $table->foreign('utilisateur_id')
                    ->references('id')
                    ->on('utilisateurs')
                    ->onDelete('cascade');
            }
            
            // Renommer la colonne services en secteur si elle existe
            if (Schema::hasColumn('infirmiers', 'services') && !Schema::hasColumn('infirmiers', 'secteur')) {
                $table->renameColumn('services', 'secteur');
            } elseif (!Schema::hasColumn('infirmiers', 'secteur')) {
                $table->string('secteur')->nullable()->after('utilisateur_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infirmiers', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            if (Schema::hasColumn('infirmiers', 'utilisateur_id')) {
                $table->dropForeign(['utilisateur_id']);
                $table->dropColumn('utilisateur_id');
            }
            
            // Rétablir le nom de la colonne si nécessaire
            if (Schema::hasColumn('infirmiers', 'secteur')) {
                $table->renameColumn('secteur', 'services');
            }
        });
    }
};

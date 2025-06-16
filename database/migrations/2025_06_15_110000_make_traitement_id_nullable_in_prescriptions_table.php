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
        // Vérifier si la colonne existe déjà
        if (Schema::hasColumn('prescriptions', 'traitement_id')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                // Rendre la colonne nullable
                $table->unsignedBigInteger('traitement_id')->nullable()->change();
            });
        } else {
            // Si la colonne n'existe pas, la créer comme nullable
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->foreignId('traitement_id')->nullable()->constrained('traitements')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vous pouvez choisir de supprimer ou de rendre non nullable dans la méthode down
        // Ici, nous allons simplement laisser la colonne telle quelle
    }
};

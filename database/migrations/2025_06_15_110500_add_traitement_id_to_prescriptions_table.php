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
        if (!Schema::hasColumn('prescriptions', 'traitement_id')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->foreignId('traitement_id')
                    ->nullable()
                    ->constrained('traitements')
                    ->onDelete('set null');
            });
        } else {
            // Si la colonne existe déjà, on s'assure qu'elle est nullable
            Schema::table('prescriptions', function (Blueprint $table) {
                $table->foreignId('traitement_id')
                    ->nullable()
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne rien faire pour éviter de perdre des données
    }
};

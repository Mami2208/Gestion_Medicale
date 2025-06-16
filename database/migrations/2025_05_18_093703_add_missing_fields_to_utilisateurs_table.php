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
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('specialite')->nullable()->after('role');
            $table->string('numero_licence')->nullable()->after('specialite');
            $table->boolean('est_actif')->default(true)->after('numero_licence');
            $table->timestamp('derniere_connexion')->nullable()->after('est_actif');
            $table->string('langue_preferee')->default('fr')->after('derniere_connexion');
            $table->json('preferences')->nullable()->after('langue_preferee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn([
                'specialite',
                'numero_licence',
                'est_actif',
                'derniere_connexion',
                'langue_preferee',
                'preferences'
            ]);
        });
    }
};

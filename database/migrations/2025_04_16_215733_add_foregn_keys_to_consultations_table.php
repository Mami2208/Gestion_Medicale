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
            $table->unsignedBigInteger('dossiers_medicaux_id')->after('id');
            $table->foreign('dossiers_medicaux_id')->references('id')->on('dossiers__medicauxes')->onDelete('cascade');

            $table->unsignedBigInteger('medecin_id')->after('dossiers_medicaux_id');
            $table->foreign('medecin_id')->references('id')->on('medecins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['dossiers_medicaux_id']);
            $table->dropColumn('dossiers_medicaux_id');

            $table->dropForeign(['medecin_id']);
            $table->dropColumn('medecin_id');
        });
    }
};

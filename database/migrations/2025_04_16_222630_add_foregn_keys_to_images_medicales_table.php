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
        Schema::table('image_medicales', function (Blueprint $table) {
            $table->unsignedBigInteger('dossiers_medicaux_id')->after('id');
            $table->foreign('dossiers_medicaux_id')->references('id')->on('dossiers__medicauxes')->onDelete('cascade');
            $table->unsignedBigInteger('examen_id')->after('id');
            $table->foreign('examen_id')->references('id')->on('examens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_medicales', function (Blueprint $table) {
             $table->dropForeign('image_medicales_dossiers_medicaux_id_foreign');
                $table->dropColumn('dossiers_medicaux_id');
                $table->dropForeign('image_medicales_examen_id_foreign');
                $table->dropColumn('examen_id');
        });
    }
};

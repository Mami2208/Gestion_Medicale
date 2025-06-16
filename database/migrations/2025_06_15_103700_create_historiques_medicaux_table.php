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
        if (!Schema::hasTable('historiques_medicaux')) {
            Schema::create('historiques_medicaux', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('patient_id');
                $table->unsignedBigInteger('dossier_medical_id');
                $table->text('antecedents_medicaux')->nullable();
                $table->text('antecedents_chirurgicaux')->nullable();
                $table->text('allergies')->nullable();
                $table->text('traitements_en_cours')->nullable();
                $table->text('habitudes_de_vie')->nullable();
                $table->text('histoire_de_la_maladie')->nullable();
                $table->timestamps();

                // Clés étrangères
                $table->foreign('patient_id')
                      ->references('id')
                      ->on('patients')
                      ->onDelete('cascade');
                      
                $table->foreign('dossier_medical_id')
                      ->references('id')
                      ->on('dossiers_medicaux')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques_medicaux');
    }
};

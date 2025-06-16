<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('medecins')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->date('date_rendez_vous');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('motif');
            $table->text('notes')->nullable();
            $table->enum('statut', ['PLANIFIE', 'CONFIRME', 'ANNULE', 'TERMINE'])->default('PLANIFIE');
            $table->timestamps();

            $table->index(['date_rendez_vous', 'heure_debut']);
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
}; 
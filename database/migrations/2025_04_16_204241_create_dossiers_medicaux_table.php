<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossiers_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('numero_dossier')->unique();
            $table->dateTime('date_creation')->default(now());
            $table->text('observations')->nullable();
            $table->json('antecedents_medicaux')->nullable();
            $table->json('allergies')->nullable();
            $table->string('groupe_sanguin')->nullable();
            $table->decimal('taille', 5, 2)->nullable(); // en cm
            $table->decimal('poids', 5, 2)->nullable(); // en kg
            $table->enum('statut', ['ACTIF', 'ARCHIVE', 'FERME'])->default('ACTIF');
            $table->timestamps();

            $table->index('numero_dossier');
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossiers_medicaux');
    }
}; 
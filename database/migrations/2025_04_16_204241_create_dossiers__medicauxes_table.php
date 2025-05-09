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
        Schema::create('dossiers__medicauxes', function (Blueprint $table) {
    $table->id();
    $table->date('dateCreation')->useCurrent();
    $table->date('dateModification')->nullable();
    $table->timestamps();
    $table->unsignedBigInteger('patient_id');
    $table->foreign('patient_id')
    ->references('id')
    ->on('patients')
    ->onDelete('cascade');
    
    // Index pour les recherches par patient
    //$table->index('patient_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers__medicauxes');
    }
};

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
        Schema::create('image_medicales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_medical_id')
                  ->constrained('dossiers__medicauxes')
                  ->onDelete('cascade');
            $table->string('type_image', 50);
            $table->boolean('is_dicom')->default(false);
            $table->timestamps();
        
            // Index composite pour les requêtes fréquentes
            $table->index(['dossier_medical_id', 'is_dicom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_medicales');
    }
};

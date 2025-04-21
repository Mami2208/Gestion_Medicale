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
        Schema::create('image__dicoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_medicale_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('orthanc_id')->unique();
            $table->string('study_uid', 128);
            $table->string('series_uid', 128);
            $table->string('modality', 20);
            $table->json('metadata')->nullable();
            $table->timestamps();
        
            // Index pour les recherches Orthanc
            $table->index('orthanc_id');
            $table->index('study_uid');
            $table->index(['modality', 'series_uid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image__dicoms');
    }
};

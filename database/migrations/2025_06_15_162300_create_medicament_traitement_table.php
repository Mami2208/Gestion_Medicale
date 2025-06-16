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
        Schema::create('medicament_traitement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicament_id')->constrained()->onDelete('cascade');
            $table->foreignId('traitement_id')->constrained()->onDelete('cascade');
            $table->string('posologie');
            $table->string('frequence');
            $table->integer('duree_jours');
            $table->text('instructions')->nullable();
            $table->timestamps();
            
            // Clé unique pour éviter les doublons
            $table->unique(['medicament_id', 'traitement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicament_traitement');
    }
};

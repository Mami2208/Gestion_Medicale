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
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->enum('specialite', [
                'Généraliste',
                'Cardiologue',
                'Chirurgien',
                'Radiologue',
                'Pédiatre',
                'Autres'
            ])->default('Généraliste');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medecins');
    }
};

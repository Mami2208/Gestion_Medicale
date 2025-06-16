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
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('forme_pharmaceutique')->nullable();
            $table->string('voie_administration')->nullable();
            $table->string('dose')->nullable();
            $table->string('unite_mesure')->default('mg');
            $table->string('code_cip')->nullable()->comment('Code Identifiant du Produit');
            $table->boolean('sur_ordonnance')->default(true);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};

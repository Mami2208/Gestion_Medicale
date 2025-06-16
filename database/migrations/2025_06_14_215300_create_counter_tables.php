<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table pour les compteurs de numéros de dossier
        Schema::create('dossier_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('sequence');
            $table->timestamps();
            
            // Index composite pour des recherches rapides
            $table->unique(['year', 'month']);
        });
        
        // Table pour les compteurs de numéros de sécurité sociale
        Schema::create('nss_counters', function (Blueprint $table) {
            $table->id();
            $table->string('birth_date_key', 8); // Format: YYYYMMDD
            $table->unsignedInteger('sequence');
            $table->timestamps();
            
            // Index pour des recherches rapides
            $table->unique('birth_date_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nss_counters');
        Schema::dropIfExists('dossier_counters');
    }
}

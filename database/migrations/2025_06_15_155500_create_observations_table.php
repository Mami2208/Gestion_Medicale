<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('infirmier_id')->nullable()->constrained('infirmiers', 'utilisateur_id')->onDelete('set null');
            $table->foreignId('medecin_id')->nullable()->constrained('medecins', 'utilisateur_id')->onDelete('set null');
            $table->text('contenu');
            $table->string('type')->default('observation'); // Peut être 'observation', 'symptome', 'remarque', etc.
            $table->string('statut')->default('actif'); // actif, archive, etc.
            $table->boolean('est_urgent')->default(false);
            $table->timestamp('date_observation')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('observations');
    }
};

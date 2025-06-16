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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('groupe_sanguin')->nullable()->after('numeroPatient');
            $table->string('allergies')->nullable()->after('groupe_sanguin');
            $table->text('antecedents_medicaux')->nullable()->after('allergies');
            $table->string('assurance_maladie')->nullable()->after('antecedents_medicaux');
            $table->string('numero_assurance')->nullable()->after('assurance_maladie');
            $table->string('medecin_traitant')->nullable()->after('numero_assurance');
            $table->string('personne_contact')->nullable()->after('medecin_traitant');
            $table->string('telephone_contact')->nullable()->after('personne_contact');
            $table->boolean('est_actif')->default(true)->after('telephone_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'groupe_sanguin',
                'allergies',
                'antecedents_medicaux',
                'assurance_maladie',
                'numero_assurance',
                'medecin_traitant',
                'personne_contact',
                'telephone_contact',
                'est_actif'
            ]);
        });
    }
}; 
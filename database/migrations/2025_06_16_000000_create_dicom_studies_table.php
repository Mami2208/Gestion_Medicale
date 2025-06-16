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
        Schema::create('dicom_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('study_uid')->unique();
            $table->dateTime('study_date');
            $table->text('description')->nullable();
            $table->json('study_data')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index pour les recherches par patient et date
            $table->index(['patient_id', 'study_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dicom_studies');
    }
};

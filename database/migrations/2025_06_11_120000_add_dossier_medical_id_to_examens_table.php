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
        Schema::table('examens', function (Blueprint $table) {
            $table->unsignedBigInteger('dossier_medical_id')->nullable()->after('id');
            $table->foreign('dossier_medical_id')
                  ->references('id')
                  ->on('dossiers_medicaux')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropForeign(['dossier_medical_id']);
            $table->dropColumn('dossier_medical_id');
        });
    }
}; 
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
        Schema::table('historiques_medicaux', function (Blueprint $table) {
            $table->dateTime('date')->nullable()->after('dossier_medical_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historiques_medicaux', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};

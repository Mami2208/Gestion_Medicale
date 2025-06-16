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
            if (!Schema::hasColumn('examens', 'numero_examen')) {
                $table->string('numero_examen', 20)->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            if (Schema::hasColumn('examens', 'numero_examen')) {
                $table->dropUnique(['numero_examen']);
                $table->dropColumn('numero_examen');
            }
        });
    }
};

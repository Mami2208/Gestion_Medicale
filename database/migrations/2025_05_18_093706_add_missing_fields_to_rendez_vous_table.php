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
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->string('type_rendez_vous')->default('CONSULTATION')->after('motif');
            $table->string('duree')->nullable()->after('type_rendez_vous');
            $table->string('lieu')->nullable()->after('duree');
            $table->string('raison_annulation')->nullable()->after('statut');
            $table->timestamp('date_confirmation')->nullable()->after('raison_annulation');
            $table->string('confirme_par')->nullable()->after('date_confirmation');
            $table->boolean('est_urgent')->default(false)->after('confirme_par');
            $table->text('instructions_preparation')->nullable()->after('est_urgent');
            $table->string('numero_salle')->nullable()->after('instructions_preparation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropColumn([
                'type_rendez_vous',
                'duree',
                'lieu',
                'raison_annulation',
                'date_confirmation',
                'confirme_par',
                'est_urgent',
                'instructions_preparation',
                'numero_salle'
            ]);
        });
    }
}; 
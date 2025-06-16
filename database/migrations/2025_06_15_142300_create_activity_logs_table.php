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
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // security, user, system, etc.
                $table->string('action'); // login_failed, user_created, etc.
                $table->text('description');
                $table->foreignId('user_id')->nullable()->constrained('utilisateurs')->onDelete('set null');
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->json('properties')->nullable(); // Données supplémentaires
                $table->timestamps();

                // Index pour améliorer les performances
                $table->index('type');
                $table->index('action');
                $table->index('user_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

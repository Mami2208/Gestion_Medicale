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
        if (!Schema::hasTable('failed_login_attempts')) {
            Schema::create('failed_login_attempts', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->string('ip_address', 45);
                $table->integer('attempt_count')->default(1);
                $table->timestamp('last_attempt_at')->useCurrent();
                $table->timestamps();

                // Index pour améliorer les performances des requêtes
                $table->index(['email', 'ip_address']);
                $table->index('last_attempt_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_login_attempts');
    }
};

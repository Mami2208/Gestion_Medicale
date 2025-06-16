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
        Schema::table('utilisateurs', function (Blueprint $table) {
            if (!Schema::hasColumn('utilisateurs', 'statut')) {
                $table->enum('statut', ['ACTIF', 'INACTIF', 'VERROUILLE'])->default('ACTIF');
            }
            
            if (!Schema::hasColumn('utilisateurs', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            
            if (!Schema::hasColumn('utilisateurs', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable();
            }
            
            if (!Schema::hasColumn('utilisateurs', 'locked_at')) {
                $table->timestamp('locked_at')->nullable();
            }
            
            if (!Schema::hasColumn('utilisateurs', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable();
            }
            
            if (!Schema::hasColumn('utilisateurs', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn([
                'statut',
                'last_login_at',
                'last_login_ip',
                'locked_at',
                'password_changed_at',
                'force_password_change'
            ]);
        });
    }
};

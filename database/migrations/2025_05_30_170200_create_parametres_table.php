<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('parametres')) {
            Schema::create('parametres', function (Blueprint $table) {
                $table->id();
                $table->string('groupe')->index();
                $table->json('valeurs');
                $table->foreignId('updated_by')->nullable()->constrained('utilisateurs')->onDelete('set null');
                $table->timestamps();
                
                // Garantir l'unicité des groupes de paramètres
                $table->unique('groupe');
            });
        }
        
        // Insérer les paramètres de sécurité par défaut
        DB::table('parametres')->updateOrInsert(
            ['groupe' => 'securite'],
            [
                'valeurs' => json_encode([
                    'password_min_length' => 8,
                    'password_expires_days' => 90,
                    'max_login_attempts' => 5,
                    'session_timeout_minutes' => 30,
                    'enable_two_factor' => false,
                    'require_captcha' => false,
                    'log_all_actions' => true,
                    'password_complexity' => 'medium',
                    'inactive_account_days' => 60,
                    'lockout_duration_minutes' => 30,
                    'password_history_count' => 5,
                    'require_special_chars' => true,
                    'require_numbers' => true,
                    'require_uppercase' => true,
                    'require_lowercase' => true,
                    'updated_at' => now()->toDateTimeString()
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};

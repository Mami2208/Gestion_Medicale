<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixLogsController extends Controller
{
    /**
     * Corrige la table des journaux d'activité et génère des exemples
     */
    public function fixAndGenerateLogs()
    {
        $message = '';
        
        // 1. Vérifier si la table existe
        if (!Schema::hasTable('activity_logs')) {
            // Créer la table si elle n'existe pas
            DB::statement('
                CREATE TABLE activity_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NULL,
                    action VARCHAR(255) NOT NULL,
                    type VARCHAR(255) NULL,
                    description TEXT NULL,
                    ip_address VARCHAR(45) NULL,
                    user_agent TEXT NULL,
                    properties JSON NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                )
            ');
            $message .= "Table activity_logs créée. ";
        } else {
            // Vérifier si la colonne type existe
            if (!Schema::hasColumn('activity_logs', 'type')) {
                DB::statement('ALTER TABLE activity_logs ADD COLUMN type VARCHAR(255) NULL AFTER action');
                $message .= "Colonne 'type' ajoutée à la table activity_logs. ";
            }
            
            // Vérifier si la colonne properties existe
            if (!Schema::hasColumn('activity_logs', 'properties')) {
                DB::statement('ALTER TABLE activity_logs ADD COLUMN properties JSON NULL AFTER user_agent');
                $message .= "Colonne 'properties' ajoutée à la table activity_logs. ";
            }
        }
        
        // 2. Nettoyer les anciens journaux
        ActivityLog::truncate();
        $message .= "Anciens journaux supprimés. ";
        
        // 3. Générer des exemples de journaux
        $this->generateSampleLogs();
        $message .= "Exemples de journaux créés.";
        
        return redirect()->route('admin.activity-logs.index')
            ->with('success', $message);
    }
    
    /**
     * Génère des exemples de journaux
     */
    private function generateSampleLogs()
    {
        // Exemple 1: Connexion
        DB::table('activity_logs')->insert([
            'action' => 'connexion',
            'type' => 'auth',
            'description' => 'Connexion réussie à l\'application',
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['browser' => 'Chrome']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 2: Échec de connexion
        DB::table('activity_logs')->insert([
            'action' => 'echec_connexion',
            'type' => 'auth',
            'description' => 'Tentative de connexion échouée: mot de passe incorrect',
            'ip_address' => '192.168.1.2',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['email' => 'test@example.com']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 3: Déconnexion
        DB::table('activity_logs')->insert([
            'action' => 'deconnexion',
            'type' => 'auth',
            'description' => 'Déconnexion de l\'application',
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 4: Création patient
        DB::table('activity_logs')->insert([
            'action' => 'creation',
            'type' => 'patient',
            'description' => 'Création d\'un nouveau patient',
            'ip_address' => '192.168.1.3',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['nom' => 'Dupont', 'prenom' => 'Jean']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 5: Modification dossier
        DB::table('activity_logs')->insert([
            'action' => 'modification',
            'type' => 'dossier',
            'description' => 'Mise à jour du dossier médical',
            'ip_address' => '192.168.1.3',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['dossier_id' => 123]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 6: Rendez-vous
        DB::table('activity_logs')->insert([
            'action' => 'creation',
            'type' => 'rdv',
            'description' => 'Nouveau rendez-vous programmé',
            'ip_address' => '192.168.1.4',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['date' => '2025-06-15', 'heure' => '14:30']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Exemple 7: Modification utilisateur
        DB::table('activity_logs')->insert([
            'action' => 'update_profile',
            'type' => 'user',
            'description' => 'Mise à jour du profil utilisateur',
            'ip_address' => '192.168.1.5',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'properties' => json_encode(['champs_modifies' => ['telephone', 'adresse']]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

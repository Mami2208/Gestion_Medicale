<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Utilisateur;

class TestLogsController extends Controller
{
    /**
     * Génère des journaux d'activité de test
     */
    public function generateLogs()
    {
        // Récupérer un utilisateur administrateur si disponible
        $admin = Utilisateur::where('role', 'ADMIN')->first();
        if (!$admin) {
            $admin = Utilisateur::first(); // Fallback si pas d'admin
        }
        
        // Connexion
        ActivityLogService::logAuth(
            'connexion',
            'Connexion réussie à l\'application',
            ['ip' => '192.168.1.1', 'user_agent' => 'Mozilla/5.0']
        );
        
        // Déconnexion
        ActivityLogService::log(
            'deconnexion',
            'auth',
            'Déconnexion de l\'application',
            null,
            ['user_id' => $admin ? $admin->id : null, 'ip' => '192.168.1.1']
        );
        
        // Échec de connexion
        ActivityLogService::log(
            'echec_connexion',
            'auth',
            'Tentative de connexion échouée : mot de passe incorrect',
            null,
            ['email' => 'test@example.com', 'ip' => '192.168.1.2']
        );
        
        // Création de patient
        ActivityLogService::log(
            'creation',
            'patient',
            'Création d\'un nouveau patient',
            null,
            ['nom' => 'Dupont', 'prenom' => 'Jean', 'id' => 123]
        );
        
        // Modification de dossier
        ActivityLogService::log(
            'modification',
            'dossier',
            'Mise à jour du dossier médical',
            null,
            ['dossier_id' => 456, 'modifications' => 'Ajout d\'antécédents']
        );
        
        // Ajout de rendez-vous
        ActivityLogService::log(
            'creation',
            'rdv',
            'Nouveau rendez-vous programmé',
            null,
            ['patient_id' => 123, 'date' => '2025-06-15', 'heure' => '14:30']
        );
        
        // Action utilisateur
        if ($admin) {
            ActivityLogService::logUser(
                'update_profile',
                'Mise à jour du profil utilisateur',
                $admin,
                ['champs_modifies' => ['telephone', 'adresse']]
            );
        }
        
        return redirect()->route('admin.activity-logs.index')
            ->with('success', '7 journaux d\'activité de test ont été générés avec succès');
    }
}

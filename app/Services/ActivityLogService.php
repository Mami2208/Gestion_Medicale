<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Enregistre une activité dans le journal
     *
     * @param string $action L'action effectuée (ex: 'connexion', 'création', 'modification', 'suppression')
     * @param string $type Le type d'entité concernée (ex: 'auth', 'user', 'patient', 'dossier', 'rdv')
     * @param string $description Description détaillée de l'action
     * @param mixed $model Le modèle concerné (optionnel)
     * @param array $properties Propriétés supplémentaires à enregistrer (optionnel)
     * @return ActivityLog
     */
    public static function log(
        string $action,
        string $type,
        string $description,
        $model = null,
        array $properties = []
    ): ActivityLog {
        $user = Auth::user();
        
        $data = [
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'type' => $type,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ];
        
        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->id;
        }
        
        if (!empty($properties)) {
            $data['properties'] = $properties;
        }
        
        return ActivityLog::create($data);
    }
    
    /**
     * Enregistre une activité d'authentification
     */
    public static function logAuth(string $action, string $description, array $properties = []): ActivityLog
    {
        return self::log($action, 'auth', $description, null, $properties);
    }
    
    /**
     * Enregistre une activité liée à un utilisateur
     */
    public static function logUser(string $action, string $description, $user, array $properties = []): ActivityLog
    {
        return self::log($action, 'user', $description, $user, $properties);
    }
    
    /**
     * Enregistre une activité liée à un patient
     */
    public static function logPatient(string $action, string $description, $patient, array $properties = []): ActivityLog
    {
        return self::log($action, 'patient', $description, $patient, $properties);
    }
    
    /**
     * Enregistre une activité liée à un dossier médical
     */
    public static function logDossier(string $action, string $description, $dossier, array $properties = []): ActivityLog
    {
        return self::log($action, 'dossier', $description, $dossier, $properties);
    }
    
    /**
     * Enregistre une activité liée à un rendez-vous
     */
    public static function logRdv(string $action, string $description, $rdv, array $properties = []): ActivityLog
    {
        return self::log($action, 'rdv', $description, $rdv, $properties);
    }
}

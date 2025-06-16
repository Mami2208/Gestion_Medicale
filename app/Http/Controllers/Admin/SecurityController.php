<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\FailedLoginAttempt;

class SecurityController extends Controller
{
    /**
     * Affiche la page des paramètres de sécurité avec statistiques et alertes
     */
    public function index()
    {
        // Récupérer les statistiques des tentatives de connexion échouées
        $failedLogins = ActivityLog::where('type', 'security')
            ->where('action', 'login_failed')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Récupérer les dernières connexions réussies
        $successfulLogins = ActivityLog::where('type', 'security')
            ->where('action', 'login_success')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Compter les utilisateurs par rôle
        $usersByRole = Utilisateur::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->get()
            ->pluck('total', 'role')
            ->toArray();
        
        // Récupérer la liste des comptes verrouillés
        $lockedAccounts = Utilisateur::where('statut', 'VERROUILLE')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        // Récupérer les paramètres de sécurité actuels
        $securitySettings = $this->getSecuritySettings();
        
        // Statistiques des activités de sécurité sur les 30 derniers jours
        $securityStats = $this->getSecurityStatistics();
        
        // Utilisateurs avec mots de passe expirés
        $expiredPasswords = $this->getExpiredPasswords();
        
        // Analyse des risques (scores de risque par utilisateur)
        $riskAnalysis = $this->analyzeSecurityRisks();
        
        return view('admin.security.modern', compact(
            'failedLogins', 
            'successfulLogins', 
            'usersByRole', 
            'lockedAccounts', 
            'securitySettings',
            'securityStats',
            'expiredPasswords',
            'riskAnalysis'
        ));
    }
    
    /**
     * Met à jour les paramètres de sécurité globaux
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'password_min_length' => 'required|integer|min:8|max:30',
            'password_expires_days' => 'required|integer|min:0|max:365',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'session_timeout_minutes' => 'required|integer|min:5|max:240',
            'enable_two_factor' => 'boolean',
            'require_captcha' => 'boolean',
            'log_all_actions' => 'boolean',
            'password_complexity' => 'required|in:low,medium,high',
            'inactive_account_days' => 'required|integer|min:0|max:365',
        ]);
        
        // Mettre à jour les paramètres dans la base de données
        DB::table('parametres')->updateOrInsert(
            ['groupe' => 'securite'],
            [
                'valeurs' => json_encode([
                    'password_min_length' => $request->password_min_length,
                    'password_expires_days' => $request->password_expires_days,
                    'max_login_attempts' => $request->max_login_attempts,
                    'session_timeout_minutes' => $request->session_timeout_minutes,
                    'enable_two_factor' => $request->has('enable_two_factor'),
                    'require_captcha' => $request->has('require_captcha'),
                    'log_all_actions' => $request->has('log_all_actions'),
                    'password_complexity' => $request->password_complexity,
                    'inactive_account_days' => $request->inactive_account_days,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'updated_by' => Auth::id()
                ]),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        );
        
        // Enregistrer l'activité via le service
        ActivityLogService::log(
            'update_settings',
            'security',
            'Mise à jour des paramètres de sécurité',
            null,
            [
                'password_min_length' => $request->password_min_length,
                'password_expires_days' => $request->password_expires_days,
                'max_login_attempts' => $request->max_login_attempts,
                'session_timeout_minutes' => $request->session_timeout_minutes,
                'enable_two_factor' => $request->has('enable_two_factor'),
                'require_captcha' => $request->has('require_captcha'),
                'log_all_actions' => $request->has('log_all_actions'),
                'password_complexity' => $request->password_complexity,
                'inactive_account_days' => $request->inactive_account_days
            ]
        );
        
        return redirect()->route('admin.security.index')->with('success', 'Paramètres de sécurité mis à jour avec succès');
    }
    
    /**
     * Réinitialise les tentatives de connexion pour un utilisateur spécifique
     */
    public function resetLoginAttempts(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        
        // Supprimer les tentatives de connexion échouées
        FailedLoginAttempt::where('user_id', $id)->delete();
        
        // Mettre à jour le statut de l'utilisateur si verrouillé
        if ($user->statut === 'VERROUILLE') {
            $user->statut = 'ACTIF';
            $user->save();
            
            // Envoyer notification à l'utilisateur
            // $user->notify(new AccountUnlocked());
        }
        
        // Enregistrer l'activité via le service
        ActivityLogService::logUser(
            'reset_login_attempts',
            'security',
            'Réinitialisation des tentatives de connexion pour ' . $user->nom . ' ' . $user->prenom,
            $user,
            [
                'previous_status' => 'VERROUILLE',
                'new_status' => 'ACTIF',
                'reset_by' => Auth::user()->nom . ' ' . Auth::user()->prenom,
                'ip_address' => $request->ip()
            ]
        );
        
        return redirect()->route('admin.security.index')->with('success', 'Tentatives de connexion réinitialisées pour ' . $user->nom . ' ' . $user->prenom);
    }
    
    /**
     * Verrouille ou déverrouille un compte utilisateur
     */
    public function toggleLock(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        $previousStatus = $user->statut;
        $user->statut = $user->statut === 'VERROUILLE' ? 'ACTIF' : 'VERROUILLE';
        $user->save();
        
        $status = $user->statut === 'ACTIF' ? 'déverrouillé' : 'verrouillé';
        
        // Si le compte est verrouillé, supprimer toutes les sessions actives
        if ($user->statut === 'VERROUILLE') {
            DB::table('sessions')->where('user_id', $id)->delete();
            
            // Enregistrer l'heure du verrouillage
            $user->locked_at = Carbon::now();
            $user->locked_by = Auth::id();
            $user->save();
            
            // Envoyer notification à l'administrateur système
            // Notification::route('mail', config('app.admin_email'))
            //    ->notify(new AccountLocked($user));
        } else {
            // Réinitialiser les champs de verrouillage
            $user->locked_at = null;
            $user->locked_by = null;
            $user->save();
            
            // Réinitialiser les tentatives de connexion
            FailedLoginAttempt::where('user_id', $id)->delete();
            
            // Envoyer notification à l'utilisateur
            // $user->notify(new AccountUnlocked());
        }
        
        // Enregistrer l'activité via le service
        ActivityLogService::log(
            $user->statut === 'ACTIF' ? 'unlock_account' : 'lock_account',
            'security',
            'Compte ' . $status . ' pour ' . $user->nom . ' ' . $user->prenom,
            $user,
            [
                'previous_status' => $previousStatus,
                'new_status' => $user->statut,
                'modified_by' => Auth::user()->nom . ' ' . Auth::user()->prenom,
                'ip_address' => $request->ip(),
                'user_role' => $user->role
            ]
        );
        
        return redirect()->route('admin.security.index')->with('success', 'Compte ' . $status . ' avec succès');
    }
}

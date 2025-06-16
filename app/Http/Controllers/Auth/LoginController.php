<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\ActivityLog;
use App\Models\FailedLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Où rediriger les utilisateurs après la connexion.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Crée une nouvelle instance du contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Affiche le formulaire de connexion
     */
    public function show()
    {
        return view('auth.login-simple');
    }
    


    /**
     * Traite la tentative de connexion avec sécurité renforcée
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        // Récupérer les paramètres de sécurité
        $securitySettings = $this->getSecuritySettings();
        
        // Vérifier si l'IP est blacklistée (trop de tentatives échouées)
        if ($this->isIpBlacklisted($request->ip())) {
            ActivityLog::create([
                'type' => 'security',
                'action' => 'ip_blocked',
                'description' => 'Tentative de connexion depuis une IP blacklistée',
                'properties' => json_encode([
                    'ip' => $request->ip(),
                    'email' => $credentials['email'],
                    'user_agent' => $request->userAgent()
                ])
            ]);
            
            return back()->withErrors([
                'email' => 'Trop de tentatives de connexion échouées. Veuillez réessayer plus tard.',
            ])->withInput($request->except('password'));
        }

        // Rechercher l'utilisateur par email
        $user = Utilisateur::where('email', $credentials['email'])->first();
        
        // Vérifier si le compte existe
        if (!$user) {
            // Enregistrer la tentative échouée pour une analyse ultérieure
            FailedLoginAttempt::record($credentials['email'], $request->ip(), $request->userAgent());
            
            ActivityLog::create([
                'type' => 'security',
                'action' => 'login_failed',
                'description' => 'Tentative de connexion échouée : compte inexistant',
                'properties' => json_encode([
                    'email' => $credentials['email'], 
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
            ]);
            
            return back()->withErrors([
                'email' => 'Aucun compte trouvé avec cet email.',
            ])->withInput($request->except('password'));
        }
        
        // Vérifier si le compte est verrouillé
        if ($user->statut === 'VERROUILLE') {
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'security',
                'action' => 'login_blocked',
                'description' => 'Tentative de connexion à un compte verrouillé',
                'properties' => json_encode([
                    'email' => $user->email, 
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'locked_at' => $user->locked_at
                ])
            ]);
            
            return back()->withErrors([
                'email' => 'Ce compte est temporairement verrouillé. Veuillez contacter l\'administrateur.',
            ])->withInput($request->except('password'));
        }
        
        // Vérifier si le compte est inactif
        if ($user->statut === 'INACTIF') {
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'security',
                'action' => 'login_inactive',
                'description' => 'Tentative de connexion à un compte inactif',
                'properties' => json_encode([
                    'email' => $user->email, 
                    'ip' => $request->ip()
                ])
            ]);
            
            return back()->withErrors([
                'email' => 'Ce compte est inactif. Veuillez contacter l\'administrateur.',
            ])->withInput($request->except('password'));
        }

        // Vérifier si le mot de passe correspond
        if (!Hash::check($credentials['password'], $user->mot_de_passe)) {
            // Enregistrer la tentative échouée
            FailedLoginAttempt::record($credentials['email'], $request->ip(), $request->userAgent());
            
            // Enregistrer l'activité dans les logs
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'security',
                'action' => 'login_failed',
                'description' => 'Tentative de connexion échouée : mot de passe incorrect',
                'properties' => json_encode([
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
            ]);
            
            return back()->withErrors([
                'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
            ])->withInput($request->except('password'));
        }
        
        // Si on arrive ici, l'authentification a réussi
        
        // Réinitialiser les tentatives de connexion échouées pour cet utilisateur
        FailedLoginAttempt::where('user_id', $user->id)->delete();
        
        // Mettre à jour les informations de dernière connexion
        $user->last_login_at = Carbon::now();
        $user->last_login_ip = $request->ip();
        $user->save();
        
        // Connecter manuellement l'utilisateur
        Auth::login($user);
        
        // Régénérer la session
        $request->session()->regenerate();
        
        // Log pour déboguer le rôle de l'utilisateur
        \Log::info('Utilisateur connecté - ID: ' . $user->id . ', Email: ' . $user->email . ', Rôle: ' . $user->role);
        \Log::info('Rôle nettoyé: [' . trim($user->role) . '] Longueur: ' . strlen(trim($user->role)));
        
        // Enregistrer l'activité de connexion
        ActivityLog::create([
            'user_id' => $user->id,
            'type' => 'security',
            'action' => 'login_success',
            'description' => 'Connexion réussie à l\'application',
            'properties' => json_encode([
                'ip' => $request->ip(), 
                'user_agent' => $request->userAgent(),
                'role' => $user->role
            ])
        ]);
        
        // Vérifier si le mot de passe doit être changé
        if ($user->force_password_change || $this->isPasswordExpired($user, $securitySettings)) {
            return redirect()->route('password.change')
                ->with('warning', 'Pour des raisons de sécurité, vous devez changer votre mot de passe.');
        }
        
        // Rediriger selon le rôle
        if (strtoupper(trim($user->role)) === 'SECRETAIRE') {
            // Nettoyer la session
            $request->session()->regenerate(true);
            
            // Forcer la connexion de l'utilisateur
            Auth::login($user);
            
            // Rediriger directement
            return redirect()->to('/secretaire/dashboard');
        }
        
        // Nettoyer le rôle
        $userRole = strtoupper(trim($user->role));
        
        // Journalisation pour le débogage
        \Log::info('Authentification réussie', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $userRole,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Déterminer la redirection en fonction du rôle
        $redirectTo = '/';
        
        switch ($userRole) {
            case 'ADMIN':
                $redirectTo = route('admin.dashboard');
                break;
                
            case 'MEDECIN':
                $redirectTo = route('medecin.dashboard');
                break;
                
            case 'INFIRMIER':
                $redirectTo = route('infirmier.dashboard');
                break;
                
            case 'PATIENT':
                $redirectTo = route('patient.dashboard');
                break;
                
            case 'SECRETAIRE':
                $redirectTo = route('secretaire.dashboard');
                break;
                
            default:
                \Log::warning('Rôle non reconnu lors de la redirection', [
                    'user_id' => $user->id,
                    'role' => $userRole
                ]);
        }
        
        // Journalisation de la redirection
        \Log::info('Redirection après authentification', [
            'user_id' => $user->id,
            'redirect_to' => $redirectTo
        ]);
        
        // Rediriger vers l'URL déterminée
        return redirect()->intended($redirectTo);
    }


        
    /**
     * Déconnecte l'utilisateur avec traçage renforcé
     */
    public function logout(Request $request)
    {
        $user = Auth::user(); // Récupérer l'utilisateur avant la déconnexion
        $userId = $user ? $user->id : null;
        $userEmail = $user ? $user->email : 'Utilisateur inconnu';
        $userRole = $user ? $user->role : 'INCONNU';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Enregistrer l'activité de déconnexion dans les logs
        ActivityLog::create([
            'user_id' => $userId,
            'type' => 'security',
            'action' => 'logout',
            'description' => 'Déconnexion de l\'application',
            'properties' => json_encode([
                'user_id' => $userId, 
                'email' => $userEmail, 
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'role' => $userRole
            ])
        ]);
        
        return redirect('/login');
    }
    
    /**
     * Récupère les paramètres de sécurité depuis la base de données
     */
    private function getSecuritySettings()
    {
        $settings = DB::table('parametres')
            ->where('groupe', 'securite')
            ->get()
            ->keyBy('cle');
            
        if ($settings->isEmpty()) {
            // Valeurs par défaut si aucun paramètre n'est défini
            return [
                'max_login_attempts' => 5,
                'session_timeout_minutes' => 30,
                'enable_two_factor' => false,
                'require_captcha' => false,
                'log_all_actions' => true,
                'password_complexity' => 'medium',
                'inactive_account_days' => 60,
                'password_expires_days' => 90 // 90 jours par défaut
            ];
        }
        
        // Construire le tableau de paramètres à partir des enregistrements
        return [
            'max_login_attempts' => (int)($settings['tentatives_connexion']->valeur ?? 5),
            'session_timeout_minutes' => (int)($settings['duree_verrouillage']->valeur ?? 30),
            'password_min_length' => (int)($settings['longueur_min_mdp']->valeur ?? 8),
            'enable_two_factor' => false,
            'require_captcha' => false,
            'log_all_actions' => true,
            'password_complexity' => 'medium',
            'inactive_account_days' => 60,
            'password_expires_days' => 90 // 90 jours avant expiration du mot de passe
        ];
    }
    
    /**
     * Vérifie si une adresse IP est blacklistée
     */
    private function isIpBlacklisted($ip)
    {
        // Récupérer les paramètres de sécurité
        $settings = $this->getSecuritySettings();
        
        // Vérifier le nombre de tentatives échouées pour cette IP dans la dernière heure
        $count = FailedLoginAttempt::where('ip_address', $ip)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->sum('attempt_count');
            
        // Si le nombre de tentatives dépasse la limite, l'IP est blacklistée
        return $count >= $settings['max_login_attempts'];
    }
    
    /**
     * Vérifie si le mot de passe d'un utilisateur a expiré
     */
    private function isPasswordExpired($user, $settings)
    {
        // Si aucune date de changement de mot de passe n'est enregistrée, considérer comme expiré
        if (!$user->password_changed_at) {
            return true;
        }
        
        // Si le nombre de jours est 0, les mots de passe n'expirent jamais
        if ($settings['password_expires_days'] <= 0) {
            return false;
        }
        
        // Vérifier si le mot de passe a expiré
        $expiryDate = Carbon::parse($user->password_changed_at)->addDays($settings['password_expires_days']);
        return Carbon::now()->greaterThan($expiryDate);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedLoginAttempt extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'ip_address',
        'attempt_count',
        'last_attempt_at'
    ];
    
    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array
     */
    protected $dates = [
        'last_attempt_at',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Désactiver l'incrémentation automatique des IDs
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Indique si le modèle doit être horodaté.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }

    /**
     * Enregistre une nouvelle tentative de connexion échouée
     *
     * @param string $email
     * @param string $ip
     * @param string $userAgent
     * @return void
     */
    /**
     * Enregistre une nouvelle tentative de connexion échouée
     *
     * @param string $email
     * @param string $ip
     * @return void
     */
    public static function record($email, $ip)
    {
        // Récupérer l'utilisateur s'il existe
        $user = Utilisateur::where('email', $email)->first();
        
        // Vérifier si une tentative existe déjà pour cette adresse IP et cet email
        $attempt = self::where('ip_address', $ip)
            ->where('email', $email)
            ->where('last_attempt_at', '>=', now()->subHour())
            ->first();

        if ($attempt) {
            // Mettre à jour le compteur et la date de dernière tentative
            $attempt->update([
                'attempt_count' => $attempt->attempt_count + 1,
                'last_attempt_at' => now()
            ]);
        } else {
            // Créer une nouvelle entrée
            $attempt = self::create([
                'email' => $email,
                'ip_address' => $ip,
                'attempt_count' => 1,
                'last_attempt_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Récupérer les paramètres de sécurité
        $maxAttempts = config('auth.max_attempts', 5);
        
        // Si l'utilisateur existe et dépasse le nombre maximum de tentatives, verrouiller le compte
        if ($user && $attempt && $attempt->attempt_count >= $maxAttempts) {
            self::lockAccount($user);
        }
    }
    
    /**
     * Verrouille un compte utilisateur après trop de tentatives
     *
     * @param Utilisateur $user
     * @return void
     */
    protected static function lockAccount($user)
    {
        // Vérifier si le compte n'est pas déjà verrouillé
        if ($user->statut !== 'VERROUILLE') {
            $user->statut = 'VERROUILLE';
            $user->locked_at = now();
            $user->save();
            
            // Journaliser l'action
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'security',
                'action' => 'account_locked',
                'description' => 'Compte verrouillé après tentatives de connexion répétées',
                'properties' => json_encode([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'lock_reason' => 'Échec de connexion multiple'
                ])
            ]);
            
            // Envoyer notification à l'administrateur (à implémenter)
            // Notification::route('mail', config('app.admin_email'))->notify(new AccountLocked($user));
        }
    }
}

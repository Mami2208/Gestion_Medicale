<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\RoleNotification;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MEDECIN = 'MEDECIN';
    const ROLE_INFIRMIER = 'INFIRMIER';
    const ROLE_SECRETAIRE = 'SECRETAIRE';

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'telephone',
        'adresse',
        'date_naissance',
        'sexe',
        'specialite',
        'photo',
        'statut',
        'force_password_change',
        'password_changed_at',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'force_password_change' => 'boolean',
        'mot_de_passe' => 'hashed',
    ];

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return strtoupper($this->role) === strtoupper($roles);
        }

        if (is_array($roles)) {
            return in_array(strtoupper($this->role), array_map('strtoupper', $roles));
        }

        return false;
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMedecin()
    {
        return $this->role === self::ROLE_MEDECIN;
    }

    public function isInfirmier()
    {
        return $this->role === self::ROLE_INFIRMIER;
    }

    public function isSecretaire()
    {
        return $this->role === self::ROLE_SECRETAIRE;
    }

    public static function getValidRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_MEDECIN,
            self::ROLE_INFIRMIER,
            self::ROLE_SECRETAIRE
        ];
    }

    public function medecin()
    {
        return $this->hasOne(\App\Models\Medecin::class, 'utilisateur_id');
    }

    public function infirmier()
    {
        return $this->hasOne(\App\Models\Infirmier::class, 'utilisateur_id');
    }

    public function secretaire_medical()
    {
        return $this->hasOne(\App\Models\Secretaire_medical::class, 'utilisateur_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'utilisateur_id');
    }
    
    /**
     * Relation avec le modèle Medecin
     */
    public function medecinRelation()
    {
        return $this->hasOne(Medecin::class, 'utilisateur_id');
    }

    public function secretaire()
    {
        return $this->hasOne(Secretaire::class);
    }

    /**
     * Get the formatted role name.
     *
     * @return string
     */
    public function getFormattedRoleAttribute()
    {
        $roles = [
            self::ROLE_ADMIN => 'Administrateur',
            self::ROLE_MEDECIN => 'Médecin',
            self::ROLE_INFIRMIER => 'Infirmier',
            self::ROLE_SECRETAIRE => 'Secrétaire'
        ];

        return $roles[$this->role] ?? $this->role;
    }

    /**
     * Get the role-specific notifications for the user.
     */
    public function roleNotifications()
    {
        return $this->hasMany(RoleNotification::class, 'user_id');
    }
}

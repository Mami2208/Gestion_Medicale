<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateur;

class RoleNotification extends Model
{
    protected $table = 'role_notifications';
    
    protected $fillable = [
        'user_id', 'titre', 'message', 'type', 'is_read'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
    ];
    
    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'user_id');
    }
    
    /**
     * Marquer la notification comme lue
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
    
    /**
     * Récupérer les notifications non lues pour un utilisateur
     */
    public static function unreadForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Créer une nouvelle notification
     */
    public static function createNotification($userId, $titre, $message, $type = 'info')
    {
        return self::create([
            'user_id' => $userId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'is_read' => false
        ]);
    }
}

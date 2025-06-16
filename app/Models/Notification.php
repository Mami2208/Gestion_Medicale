<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Spécifier explicitement le nom de la table (probablement "notifs" ou "alertes")
    protected $table = 'notifs';
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'is_read',
        'data',
        'dateEnvoi',
        'typeLecture',
        'medecin_id'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'dateEnvoi' => 'datetime'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'dateEnvoi'
    ];
    
    /**
     * Relation avec l'utilisateur destinataire
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

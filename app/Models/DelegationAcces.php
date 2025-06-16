<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateur;
use App\Models\Medecin;

class DelegationAcces extends Model
{
    protected $table = 'delegations_acces';
    
    protected $fillable = [
        'medecin_id', 'infirmier_id', 'patient_id', 
        'date_debut', 'date_fin', 'raison', 'statut'
    ];
    
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];
    
    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_id', 'utilisateur_id');
    }
    
    public function infirmier()
    {
        return $this->belongsTo(Utilisateur::class, 'infirmier_id');
    }
    
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
    public function isActive()
    {
        return $this->statut === 'active' && 
               $this->date_debut <= now() && 
               $this->date_fin >= now();
    }
}

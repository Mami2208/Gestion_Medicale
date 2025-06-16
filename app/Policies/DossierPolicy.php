<?php

namespace App\Policies;

use App\Models\Dossier;
use App\Models\Utilisateur;
use Illuminate\Auth\Access\Response;

class DossierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Utilisateur $utilisateur): bool
    {
        // Seuls les médecins et les administrateurs peuvent voir la liste des dossiers
        return in_array($utilisateur->role, ['MEDECIN', 'ADMIN']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Utilisateur $utilisateur, Dossier $dossier): bool
    {
        // L'utilisateur peut voir le dossier s'il est médecin et qu'il est le propriétaire du dossier
        // ou s'il est le patient propriétaire du dossier
        // ou s'il est administrateur
        return $utilisateur->role === 'ADMIN' ||
               ($utilisateur->role === 'MEDECIN' && $utilisateur->medecinRelation && $dossier->medecin_id === $utilisateur->medecinRelation->id) ||
               ($utilisateur->role === 'PATIENT' && $utilisateur->patient && $dossier->patient_id === $utilisateur->patient->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Utilisateur $utilisateur): bool
    {
        // Seuls les médecins peuvent créer des dossiers
        return $utilisateur->role === 'MEDECIN' || $utilisateur->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Utilisateur $utilisateur, Dossier $dossier): bool
    {
        // Seul le médecin propriétaire du dossier ou un administrateur peut le modifier
        return $utilisateur->role === 'ADMIN' ||
               ($utilisateur->role === 'MEDECIN' && $utilisateur->medecinRelation && $dossier->medecin_id === $utilisateur->medecinRelation->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Utilisateur $utilisateur, Dossier $dossier): bool
    {
        // Seul un administrateur peut supprimer un dossier
        return $utilisateur->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Utilisateur $utilisateur, Dossier $dossier): bool
    {
        // Seul un administrateur peut restaurer un dossier
        return $utilisateur->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Utilisateur $utilisateur, Dossier $dossier): bool
    {
        // Seul un administrateur peut supprimer définitivement un dossier
        return $utilisateur->role === 'ADMIN';
    }
}

<?php

namespace App\Policies;

use App\Models\DicomStudy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DicomPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir les études DICOM
     */
    public function viewAny(User $user): bool
    {
        return $user->isMedecin() || $user->isPatient();
    }

    /**
     * Détermine si l'utilisateur peut voir une étude DICOM spécifique
     */
    public function view(User $user, DicomStudy $study): bool
    {
        // Le médecin voit tout
        if ($user->isMedecin()) {
            return true;
        }
        
        // Le patient ne voit que ses propres études
        return $user->isPatient() && $user->id === $study->patient_id;
    }

    /**
     * Détermine si l'utilisateur peut créer des études DICOM
     */
    public function create(User $user): bool
    {
        // Seuls les médecins peuvent créer des études DICOM
        return $user->isMedecin();
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une étude DICOM
     */
    public function update(User $user, DicomStudy $dicomStudy): bool
    {
        // Seuls les médecins peuvent mettre à jour les études
        return $user->isMedecin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer une étude DICOM
     */
    public function delete(User $user, DicomStudy $dicomStudy): bool
    {
        // Seuls les médecins peuvent supprimer les études
        return $user->isMedecin();
    }
}

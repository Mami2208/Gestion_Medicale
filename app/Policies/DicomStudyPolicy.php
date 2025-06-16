<?php

namespace App\Policies;

use App\Models\DicomStudy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DicomStudyPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir l'étude DICOM.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DicomStudy  $dicomStudy
     * @return mixed
     */
    public function view(User $user, DicomStudy $dicomStudy)
    {
        // L'utilisateur peut voir l'étude s'il est médecin et associé à la consultation
        if ($user->role === 'MEDECIN') {
            return $user->medecin && $user->medecin->consultations->contains('id', $dicomStudy->consultation_id);
        }
        
        // L'utilisateur peut voir l'étude s'il est administrateur
        if ($user->role === 'ADMIN') {
            return true;
        }
        
        // L'utilisateur peut voir l'étude s'il est le patient associé à la consultation
        if ($user->role === 'PATIENT') {
            return $user->patient && $user->patient->consultations->contains('id', $dicomStudy->consultation_id);
        }
        
        // Par défaut, refuser l'accès
        return false;
    }

    /**
     * Détermine si l'utilisateur peut créer des études DICOM.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Seuls les médecins et les administrateurs peuvent créer des études DICOM
        return in_array($user->role, ['MEDECIN', 'ADMIN']);
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour l'étude DICOM.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DicomStudy  $dicomStudy
     * @return mixed
     */
    public function update(User $user, DicomStudy $dicomStudy)
    {
        // Seul le médecin qui a créé la consultation peut mettre à jour l'étude
        if ($user->role === 'MEDECIN') {
            return $user->medecin && $user->medecin->consultations->contains('id', $dicomStudy->consultation_id);
        }
        
        // Les administrateurs peuvent tout modifier
        return $user->role === 'ADMIN';
    }

    /**
     * Détermine si l'utilisateur peut supprimer l'étude DICOM.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DicomStudy  $dicomStudy
     * @return mixed
     */
    public function delete(User $user, DicomStudy $dicomStudy)
    {
        // Même logique que pour la mise à jour
        return $this->update($user, $dicomStudy);
    }

    /**
     * Détermine si l'utilisateur peut restaurer l'étude DICOM.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DicomStudy  $dicomStudy
     * @return mixed
     */
    public function restore(User $user, DicomStudy $dicomStudy)
    {
        return $this->update($user, $dicomStudy);
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement l'étude DICOM.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DicomStudy  $dicomStudy
     * @return mixed
     */
    public function forceDelete(User $user, DicomStudy $dicomStudy)
    {
        // Seul l'administrateur peut supprimer définitivement une étude
        return $user->role === 'ADMIN';
    }
}

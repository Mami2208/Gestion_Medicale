<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\Utilisateur;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultationPolicy
{
    use HandlesAuthorization;

    public function view(Utilisateur $user, Consultation $consultation)
    {
        return $user->medecin && $user->medecin->id === $consultation->medecin_id;
    }

    public function update(Utilisateur $user, Consultation $consultation)
    {
        return $user->medecin && $user->medecin->id === $consultation->medecin_id;
    }

    public function delete(Utilisateur $user, Consultation $consultation)
    {
        return $user->medecin && $user->medecin->id === $consultation->medecin_id;
    }
}
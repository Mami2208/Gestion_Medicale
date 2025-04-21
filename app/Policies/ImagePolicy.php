<?php

namespace App\Policies;

use App\Models\ImageMedicale;
use App\Models\Utilisateur;

class ImagePolicy
{
    public function view(Utilisateur $user, ImageMedicale $image)
    {
        return $user->role === 'MEDECIN' || 
                $image->dossierMedical->patient->utilisateur_id === $user->id;
    }
}
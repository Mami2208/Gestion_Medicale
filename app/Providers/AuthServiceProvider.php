<?php

namespace App\Providers;

use App\Models\Dossier;
use App\Models\ImageMedicale;
use App\Models\DicomStudy;
use App\Policies\DossierPolicy;
use App\Policies\ImagePolicy;
use App\Policies\DicomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Dossier::class => DossierPolicy::class,
        ImageMedicale::class => ImagePolicy::class,
        DicomStudy::class => DicomPolicy::class,
        // Add other model-policy mappings here if needed
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('upload-dicom', function ($user) {
            return in_array($user->role, ['MEDECIN', 'ADMIN']);
        });
    }
}

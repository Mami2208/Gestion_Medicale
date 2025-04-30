<?php

namespace App\Providers;

use App\Models\ImageMedicale;
use App\Policies\ImagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ImageMedicale::class => ImagePolicy::class,
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

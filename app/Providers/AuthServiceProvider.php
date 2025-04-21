<?php

namespace App\Providers;

use App\Models\ImageMedicale;
use App\Policies\ImagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ImageMedicale::class => ImagePolicy::class,
        // Ajoutez ici d'autres modèles/policies si nécessaire
    ];

    public function boot()
    {
        $this->registerPolicies();
        
        // Ajoutez d'autres configurations d'autorisation ici
    }
}
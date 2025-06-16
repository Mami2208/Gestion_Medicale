<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OrthancClient;

class OrthancServiceProvider extends ServiceProvider
{
    /**
     * Enregistre les services de l'application.
     */
    public function register()
    {
        $this->app->singleton(OrthancClient::class, function ($app) {
            return new OrthancClient();
        });
    }

    /**
     * Démarre les services de l'application.
     */
    public function boot()
    {
        // Configuration par défaut pour Orthanc
        $this->mergeConfigFrom(
            __DIR__.'/../../config/orthanc.php', 'orthanc'
        );
    }
}

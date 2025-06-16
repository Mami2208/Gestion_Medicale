<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Orthanc Configuration
    |--------------------------------------------------------------------------
    |
    | Cette configuration définit les paramètres de connexion à Orthanc DICOM Server.
    |
    */

    // URL de base du serveur Orthanc (sans le / à la fin)
    'base_url' => env('ORTHANC_URL', 'http://localhost:8042'),
    
    // Nom d'utilisateur pour l'authentification HTTP Basic (laisser vide si non configuré)
    'username' => env('ORTHANC_USER', ''),
    
    // Mot de passe pour l'authentification HTTP Basic (laisser vide si non configuré)
    'password' => env('ORTHANC_PASS', ''),
    
    // URL du visualiseur web Orthanc (si installé séparément)
    'web_viewer_url' => env('ORTHANC_WEB_VIEWER_URL', 'http://localhost:8042/app/explorer.html'),
    
    // Configuration du cache (en secondes)
    'cache_ttl' => env('ORTHANC_CACHE_TTL', 3600), // 1 heure par défaut
    
    // Configuration des dossiers de stockage temporaire
    'temp_path' => storage_path('app/orthanc/temp'),
    'export_path' => storage_path('app/orthanc/exports'),
    
    // Paramètres de connexion HTTP
    'timeout' => 60, // secondes
    'connect_timeout' => 10, // secondes
    
    // Options de débogage
    'debug' => env('APP_DEBUG', false),
    
    // Configuration du proxy (si nécessaire)
    'proxy' => [
        'http' => env('HTTP_PROXY', null),
        'https' => env('HTTPS_PROXY', null),
        'no' => explode(',', env('NO_PROXY', '')),
    ],
];

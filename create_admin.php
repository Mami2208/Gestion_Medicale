<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

try {
    $admin = Utilisateur::create([
        'nom' => 'Admin',
        'prenom' => 'Super',
        'email' => 'admin@gmail.com',
        'mot_de_passe' => Hash::make('admin123'),
        'role' => 'ADMIN',
        'telephone' => '+1234567890'
    ]);

    echo "Administrateur créé avec succès !\n";
    echo "Email : admin@gmail.com\n";
    echo "Mot de passe : admin123\n";
} catch (\Exception $e) {
    echo "Erreur lors de la création de l'administrateur : " . $e->getMessage() . "\n";
} 
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

$admin = Utilisateur::where('role', 'ADMIN')->first();

if ($admin) {
    $admin->mot_de_passe = Hash::make('admin123');
    $admin->save();
    echo "Mot de passe de l'administrateur mis à jour avec succès.\n";
    echo "Nouveau mot de passe : admin123\n";
} else {
    echo "Aucun administrateur trouvé dans la base de données.\n";
} 
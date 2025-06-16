<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Utilisateur;
use App\Models\Medecin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    // Création de l'utilisateur
    $utilisateur = Utilisateur::create([
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'jean.dupont@gmail.com',
        'mot_de_passe' => Hash::make('medecin123'),
        'role' => 'MEDECIN',
        'telephone' => '+33612345678'
    ]);

    // Création du médecin
    $medecin = Medecin::create([
        'utilisateur_id' => $utilisateur->id,
        'matricule' => 'MED-' . str_pad($utilisateur->id, 4, '0', STR_PAD_LEFT),
        'specialite' => 'GENERALISTE'
    ]);

    DB::commit();

    echo "Médecin créé avec succès !\n";
    echo "Email : jean.dupont@gmail.com\n";
    echo "Mot de passe : medecin123\n";
    echo "Matricule : " . $medecin->matricule . "\n";
    echo "Spécialité : Généraliste\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "Erreur lors de la création du médecin : " . $e->getMessage() . "\n";
} 
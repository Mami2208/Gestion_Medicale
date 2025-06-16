<?php

use App\Models\DelegationAcces;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Connexion à la base de données Laravel
require __DIR__.'/../../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Démarrer une transaction
    DB::beginTransaction();

    Log::info('Début du nettoyage des délégations en double');

    // 1. Identifier les doublons
    $duplicates = DB::table('delegations_acces')
        ->select('medecin_id', 'infirmier_id', 'patient_id', DB::raw('COUNT(*) as count'))
        ->where('statut', 'active')
        ->groupBy('medecin_id', 'infirmier_id', 'patient_id')
        ->having('count', '>', 1)
        ->get();

    Log::info('Groupes de doublons trouvés : ' . $duplicates->count());

    $totalDeleted = 0;

    foreach ($duplicates as $duplicate) {
        Log::info("Traitement du groupe : ", [
            'medecin_id' => $duplicate->medecin_id,
            'infirmier_id' => $duplicate->infirmier_id,
            'patient_id' => $duplicate->patient_id,
            'count' => $duplicate->count
        ]);

        // 2. Pour chaque groupe de doublons, garder le plus récent et supprimer les autres
        $delegationsToKeep = DelegationAcces::where([
                'medecin_id' => $duplicate->medecin_id,
                'infirmier_id' => $duplicate->infirmier_id,
                'patient_id' => $duplicate->patient_id,
                'statut' => 'active'
            ])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($delegationsToKeep) {
            $deleted = DelegationAcces::where([
                    'medecin_id' => $duplicate->medecin_id,
                    'infirmier_id' => $duplicate->infirmier_id,
                    'patient_id' => $duplicate->patient_id,
                    'statut' => 'active'
                ])
                ->where('id', '!=', $delegationsToKeep->id)
                ->delete();

            $totalDeleted += $deleted;
            Log::info("Suppression de $doublons supprimés pour ce groupe");
        }
    }

    // Valider la transaction
    DB::commit();

    Log::info("Nettoyage terminé. Total des doublons supprimés : $totalDeleted");
    echo "Nettoyage terminé. Total des doublons supprimés : $totalDeleted\n";

} catch (\Exception $e) {
    // En cas d'erreur, annuler la transaction
    DB::rollBack();
    
    Log::error('Erreur lors du nettoyage des délégations : ' . $e->getMessage());
    Log::error($e->getTraceAsString());
    
    echo "Une erreur est survenue : " . $e->getMessage() . "\n";
    exit(1);
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegationAcces;
use Illuminate\Support\Facades\Log;

class DeleteDelegation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delegation:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime une délégation spécifique par son ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        // Récupérer la délégation
        $delegation = DelegationAcces::find($id);
        
        if (!$delegation) {
            $this->error("Aucune délégation trouvée avec l'ID: {$id}");
            return 1;
        }
        
        // Afficher les informations de la délégation
        $this->info("Délégation à supprimer :");
        $this->table(
            ['ID', 'Médecin ID', 'Infirmier ID', 'Patient ID', 'Statut', 'Date création'],
            [
                [
                    'id' => $delegation->id,
                    'medecin_id' => $delegation->medecin_id,
                    'infirmier_id' => $delegation->infirmier_id,
                    'patient_id' => $delegation->patient_id,
                    'statut' => $delegation->statut,
                    'created_at' => $delegation->created_at->format('Y-m-d H:i:s'),
                ]
            ]
        );
        
        if ($this->confirm('Voulez-vous vraiment supprimer cette délégation ?')) {
            try {
                $delegation->delete();
                $this->info("Délégation #{$delegation->id} supprimée avec succès.");
                Log::info("Délégation #{$delegation->id} supprimée via la commande artisan");
            } catch (\Exception $e) {
                $this->error("Erreur lors de la suppression de la délégation #{$delegation->id}: " . $e->getMessage());
                Log::error("Erreur suppression délégation #{$delegation->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return 1;
            }
        } else {
            $this->info('Suppression annulée.');
        }
        
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegationAcces;
use Illuminate\Support\Facades\Log;

class DeleteLastDelegations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delegations:delete-last {count=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les N dernières délégations (par défaut 3)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int)$this->argument('count');
        
        if ($count <= 0) {
            $this->error('Le nombre de délégations à supprimer doit être supérieur à 0');
            return 1;
        }
        
        // Récupérer les N dernières délégations
        $delegations = DelegationAcces::orderBy('id', 'desc')->take($count)->get();
        
        if ($delegations->isEmpty()) {
            $this->info('Aucune délégation trouvée à supprimer.');
            return 0;
        }
        
        $this->info("Délégations à supprimer :");
        $this->table(
            ['ID', 'Médecin ID', 'Infirmier ID', 'Patient ID', 'Statut', 'Date création'],
            $delegations->map(function ($delegation) {
                return [
                    'id' => $delegation->id,
                    'medecin_id' => $delegation->medecin_id,
                    'infirmier_id' => $delegation->infirmier_id,
                    'patient_id' => $delegation->patient_id,
                    'statut' => $delegation->statut,
                    'created_at' => $delegation->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray()
        );
        
        if ($this->confirm('Voulez-vous vraiment supprimer ces délégations ?')) {
            $deleted = 0;
            
            foreach ($delegations as $delegation) {
                try {
                    $delegation->delete();
                    $deleted++;
                    $this->line("Délégation #{$delegation->id} supprimée");
                    Log::info("Délégation #{$delegation->id} supprimée via la commande artisan");
                } catch (\Exception $e) {
                    $this->error("Erreur lors de la suppression de la délégation #{$delegation->id}: " . $e->getMessage());
                    Log::error("Erreur suppression délégation #{$delegation->id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            $this->info("\n{$deleted} délégation(s) supprimée(s) avec succès.");
        } else {
            $this->info('Suppression annulée.');
        }
        
        return 0;
    }
}

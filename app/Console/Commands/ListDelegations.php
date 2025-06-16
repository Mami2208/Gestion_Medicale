<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DelegationAcces;

class ListDelegations extends Command
{
    protected $signature = 'delegations:list';
    protected $description = 'Lister toutes les délégations';

    public function handle()
    {
        $delegations = DelegationAcces::all();
        
        if ($delegations->isEmpty()) {
            $this->info('Aucune délégation trouvée.');
            return 0;
        }
        
        $this->info('Liste des délégations :');
        
        $rows = [];
        foreach ($delegations as $delegation) {
            $rows[] = [
                'ID' => $delegation->id,
                'Médecin ID' => $delegation->medecin_id,
                'Infirmier ID' => $delegation->infirmier_id,
                'Patient ID' => $delegation->patient_id,
                'Statut' => $delegation->statut,
                'Créée le' => $delegation->created_at->format('Y-m-d H:i:s'),
            ];
        }
        
        $this->table(
            ['ID', 'Médecin ID', 'Infirmier ID', 'Patient ID', 'Statut', 'Créée le'],
            $rows
        );
        
        return 0;
    }
}

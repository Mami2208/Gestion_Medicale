<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Mettre à jour le rôle 'secretaire' en 'SECRETAIRE'
        DB::table('utilisateurs')
            ->where('role', 'secretaire')
            ->update(['role' => 'SECRETAIRE']);
            
        // Vérifier et mettre à jour d'autres rôles si nécessaire
        $rolesToUpdate = [
            'admin' => 'ADMIN',
            'medecin' => 'MEDECIN',
            'infirmier' => 'INFIRMIER',
            'patient' => 'PATIENT'
        ];
        
        foreach ($rolesToUpdate as $lower => $upper) {
            DB::table('utilisateurs')
                ->where('role', $lower)
                ->update(['role' => $upper]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Pas besoin de rollback, mais on peut le faire si nécessaire
        DB::table('utilisateurs')
            ->where('role', 'SECRETAIRE')
            ->update(['role' => 'secretaire']);
    }
};

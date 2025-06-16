<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vu00e9rifier si la table existe
        if (Schema::hasTable('activity_logs')) {
            // Vu00e9rifier si la colonne 'type' existe du00e9ju00e0
            if (!Schema::hasColumn('activity_logs', 'type')) {
                // Ajouter la colonne 'type' u00e0 la table
                DB::statement('ALTER TABLE `activity_logs` ADD COLUMN `type` VARCHAR(255) NULL AFTER `action`');
                
                $this->command->info("Colonne 'type' ajoute\u00e9e u00e0 la table activity_logs.");
            } else {
                $this->command->info("La colonne 'type' existe de\u00e9ju00e0 dans la table activity_logs.");
            }
        } else {
            $this->command->error("La table activity_logs n'existe pas.");
        }
    }
}

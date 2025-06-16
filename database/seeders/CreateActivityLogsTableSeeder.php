<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vu00e9rifier si la table existe du00e9ju00e0
        if (!Schema::hasTable('activity_logs')) {
            // Cru00e9er la table activity_logs
            DB::statement('
                CREATE TABLE `activity_logs` (
                    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `user_id` bigint(20) UNSIGNED NULL,
                    `action` varchar(255) NOT NULL,
                    `model_type` varchar(255) NULL,
                    `model_id` bigint(20) UNSIGNED NULL,
                    `description` text NULL,
                    `properties` json NULL,
                    `ip_address` varchar(45) NULL,
                    `user_agent` varchar(255) NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `activity_logs_user_id_foreign` (`user_id`),
                    KEY `activity_logs_model_type_model_id_index` (`model_type`, `model_id`),
                    CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ');
            
            $this->command->info("Table activity_logs cru00e9u00e9e avec succu00e8s.");
        } else {
            $this->command->info("La table activity_logs existe du00e9ju00e0.");
        }
    }
}

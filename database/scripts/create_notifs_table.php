<?php
// Configuration de la connexion à la base de données
$config = include 'c:/xampp/htdocs/Gestion_Medicale/config/database.php';
$connection = $config['connections'][$config['default']];

try {
    // Création de la connexion PDO
    $dsn = "mysql:host={$connection['host']};dbname={$connection['database']};charset={$connection['charset']}";
    $pdo = new PDO($dsn, $connection['username'], $connection['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la table existe déjà
    $tableExists = $pdo->query("SHOW TABLES LIKE 'notifs'")->rowCount() > 0;

    if (!$tableExists) {
        // Création de la table notifs
        $sql = "CREATE TABLE `notifs` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint unsigned NOT NULL,
            `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `is_read` tinyint(1) NOT NULL DEFAULT '0',
            `data` json DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `notifs_user_id_foreign` (`user_id`),
            CONSTRAINT `notifs_user_id_foreign` 
                FOREIGN KEY (`user_id`) 
                REFERENCES `users` (`id`) 
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $pdo->exec($sql);
    }

} catch (PDOException $e) {
    // Enregistrer l'erreur dans un fichier de log
    file_put_contents('c:/xampp/htdocs/Gestion_Medicale/storage/logs/database_errors.log', 
        '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . "\n", 
        FILE_APPEND
    );
}

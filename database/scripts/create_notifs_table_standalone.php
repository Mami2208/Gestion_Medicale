<?php
// Configuration de la base de données (à adapter selon votre configuration)
$dbConfig = [
    'host' => 'localhost',
    'database' => 'gestion_medicale',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    // Création de la connexion PDO
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $pdo->exec($sql);
        echo "La table 'notifs' a été créée avec succès.\n";
    } else {
        echo "La table 'notifs' existe déjà.\n";
    }

} catch (PDOException $e) {
    echo "Erreur lors de la création de la table : " . $e->getMessage() . "\n";
    // Enregistrer l'erreur dans un fichier de log
    file_put_contents('c:/xampp/htdocs/Gestion_Medicale/storage/logs/database_errors.log', 
        '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . "\n", 
        FILE_APPEND
    );
}

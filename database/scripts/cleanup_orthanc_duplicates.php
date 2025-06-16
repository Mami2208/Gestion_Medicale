<?php
// Configuration de la base de données Orthanc
$dbConfig = [
    'host' => 'localhost',
    'database' => 'orthanc',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];

try {
    // Connexion à la base de données
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Désactiver temporairement la vérification des clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
    
    // Démarrer la transaction
    $pdo->beginTransaction();
    
    echo "Début du nettoyage des délégations en double dans la base Orthanc...\n";
    
    // 1. Identifier les groupes de doublons
    $query = "
        SELECT 
            medecin_id, 
            infirmier_id, 
            patient_id, 
            COUNT(*) as count
        FROM delegations_acces
        WHERE statut = 'active'
        GROUP BY medecin_id, infirmier_id, patient_id
        HAVING count > 1
    ";
    
    $stmt = $pdo->query($query);
    $duplicates = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $totalDeleted = 0;
    
    echo "Groupes de doublons trouvés : " . count($duplicates) . "\n";
    
    foreach ($duplicates as $duplicate) {
        echo sprintf(
            "Traitement du groupe - Médecin: %d, Infirmier: %d, Patient: %d, Doublons: %d\n",
            $duplicate->medecin_id,
            $duplicate->infirmier_id,
            $duplicate->patient_id,
            $duplicate->count - 1
        );
        
        // 2. Pour chaque groupe, trouver l'ID le plus récent à conserver
        $keepQuery = "
            SELECT id 
            FROM delegations_acces 
            WHERE 
                medecin_id = :medecin_id AND
                infirmier_id = :infirmier_id AND
                patient_id = :patient_id AND
                statut = 'active'
            ORDER BY created_at DESC
            LIMIT 1
        ";
        
        $keepStmt = $pdo->prepare($keepQuery);
        $keepStmt->execute([
            ':medecin_id' => $duplicate->medecin_id,
            ':infirmier_id' => $duplicate->infirmier_id,
            ':patient_id' => $duplicate->patient_id
        ]);
        
        $delegationToKeep = $keepStmt->fetch(PDO::FETCH_OBJ);
        
        if ($delegationToKeep) {
            // 3. Supprimer les doublons sauf celui qu'on garde
            $deleteQuery = "
                DELETE FROM delegations_acces 
                WHERE 
                    medecin_id = :medecin_id AND
                    infirmier_id = :infirmier_id AND
                    patient_id = :patient_id AND
                    statut = 'active' AND
                    id != :keep_id
            ";
            
            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->execute([
                ':medecin_id' => $duplicate->medecin_id,
                ':infirmier_id' => $duplicate->infirmier_id,
                ':patient_id' => $duplicate->patient_id,
                ':keep_id' => $delegationToKeep->id
            ]);
            
            $deleted = $deleteStmt->rowCount();
            $totalDeleted += $deleted;
            
            echo "  - Supprimé : $deleted doublon(s)\n";
        }
    }
    
    // Valider la transaction
    $pdo->commit();
    
    // Réactiver la vérification des clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    
    echo "\nNettoyage terminé avec succès !\n";
    echo "Total des doublons supprimés : $totalDeleted\n";
    
} catch (PDOException $e) {
    // En cas d'erreur, annuler la transaction
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
    }
    
    echo "\nErreur lors du nettoyage des délégations :\n";
    echo $e->getMessage() . "\n";
    exit(1);
}

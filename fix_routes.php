<?php
// Script pour vu00e9rifier et corriger les accolades dans le fichier des routes
$file = 'routes/web.php';
$content = file_get_contents($file);

// Compter les accolades ouvrantes et fermantes
$open_count = substr_count($content, '{');
$close_count = substr_count($content, '}');

echo "Accolades ouvrantes: {$open_count}\n";
echo "Accolades fermantes: {$close_count}\n";

if ($open_count > $close_count) {
    echo "Il manque " . ($open_count - $close_count) . " accolade(s) fermante(s).\n";
    // Ajouter une accolade fermante u00e0 la fin du fichier
    file_put_contents($file, $content . "\n}");
    echo "Accolade fermante ajoute\u00e9e u00e0 la fin du fichier.\n";
} elseif ($close_count > $open_count) {
    echo "Il y a " . ($close_count - $open_count) . " accolade(s) fermante(s) en trop.\n";
} else {
    echo "Le nombre d'accolades est u00e9quilibru00e9.\n";
}

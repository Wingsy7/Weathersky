<?php
// Lire le fichier CSV et compter les villes
$fichier = 'villes_consultées.csv';
$villes = [];

if (($handle = fopen($fichier, 'r')) !== false) {
    // Ignorer l'en-tête
    fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        $ville = $data[0];
        $villes[$ville] = ($villes[$ville] ?? 0) + 1;
    }
    fclose($handle);
}

// Préparer les données pour l'histogramme
$labels = array_keys($villes);
$valeurs = array_values($villes);
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des villes</title>
    <style>
        .bar {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            margin: 5px 0;
        }
    </style>
</head>
    <h1>Statistiques des villes consultées</h1>
    <?php
    if (!empty($villes)) {
        $max = max($valeurs); // Pour normaliser la largeur des barres
        foreach ($villes as $ville => $count) {
            $width = ($count / $max) * 300; // Largeur max de 300px
            echo "<div class='bar' style='width: {$width}px;'>{$ville} ({$count})</div>";
        }
    } else {
        echo "<p>Aucune donnée disponible.</p>";
    }
    ?>
    <p><a href="index.php">Retour</a></p>
</html>

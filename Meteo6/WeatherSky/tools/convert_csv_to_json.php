<?php
/**
 * Convertit un fichier CSV de villes en fichier JSON formaté.
 *
 * @param string $fichierCSV Chemin vers le fichier CSV source
 * @param string $fichierJSON Chemin vers le fichier JSON de sortie
 */
function convertirVillesCSV($fichierCSV, $fichierJSON) {
    // Lit le fichier CSV et convertit chaque ligne en tableau
    $csv = array_map('str_getcsv', file($fichierCSV));

    // Récupère la première ligne comme entêtes (noms de colonnes)
    $entetes = array_map('trim', $csv[0]);

    // Tableau pour stocker les données converties
    $donnees = [];

    // Recherche des index des colonnes importantes dans les entêtes
    $indexNom = array_search('nom', $entetes);
    $indexCodePostal = array_search('code_postal', $entetes);
    $indexLat = array_search('latitude', $entetes);
    $indexLon = array_search('longitude', $entetes);

    // Parcours de chaque ligne de données (à partir de la 2ème ligne)
    for ($i = 1; $i < count($csv); $i++) {
        $ligne = $csv[$i];

        // Vérifie que toutes les colonnes nécessaires sont présentes
        if (!isset($ligne[$indexNom], $ligne[$indexCodePostal], $ligne[$indexLat], $ligne[$indexLon])) {
            continue; // Ignore les lignes incomplètes
        }

        // Ajoute les données formatées dans le tableau final
        $donnees[] = [
            "Nom_commune" => $ligne[$indexNom],
            "Code_postal" => $ligne[$indexCodePostal],
            "coordonnees_gps" => $ligne[$indexLat] . "," . $ligne[$indexLon]
        ];
    }

    // Encode les données en JSON et les enregistre dans le fichier cible
    file_put_contents($fichierJSON, json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Affiche un message de succès
    echo "✅ Fichier $fichierJSON généré avec succès.\n";
}

// Utilisation de la fonction : conversion du fichier CSV vers JSON
convertirVillesCSV("../villes.csv", "../villes.json");

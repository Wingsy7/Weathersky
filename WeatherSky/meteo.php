<?php
/* Requires and includes */
include "../include/functions.inc.php";
include "../include/util.inc.php"; // Inclure le fichier util.php

// Récupérer les informations du navigateur
$browser_info = get_browser_info();
$loc = getBestVisitorLocation();

if (!$loc || !isset($loc['latitude']) || !isset($loc['longitude'])) {
    die("Impossible de récupérer votre position.");
}

$lat = $loc['latitude'];
$lon = $loc['longitude'];
$ville = $loc['city'] ?? "Your city";

// Appel Open-Meteo
$meteo_url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&daily=temperature_2m_max,temperature_2m_min&timezone=Europe%2FParis";
$meteoData = json_decode(file_get_contents($meteo_url), true);

$dates = $meteoData['daily']['time'];
$tempMax = $meteoData['daily']['temperature_2m_max'];
$tempMin = $meteoData['daily']['temperature_2m_min'];
?>

<main>
    <div class="card">
        <h2>Météo à <?= htmlspecialchars($ville) ?></h2>
        <ul>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <li>
                    <strong><?= $dates[$i] ?> :</strong>
                    Min <?= $tempMin[$i] ?>°C / Max <?= $tempMax[$i] ?>°C
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</main>

<footer>
    <p class="copyright">Cozma Miroslav, étudiant à <a href="https://www.cyu.fr/">Cergy Université</a>.</p>
    <p>Dernière mise à jour: <time datetime="2025-03-25">25/03/2025</time></p>
    <p>L2-Informatique</p>
    <a href="../index.php"><p>Accueil</p></a>
    <a href="../sitemap.php"><p>Plan du Site</p></a>
    <p id="browser-info"><?= htmlspecialchars($browser_info) ?></p>
</footer>
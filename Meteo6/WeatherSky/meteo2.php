<?php 
// Inclusions nécessaires
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");

// Chargement des données CSV
$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");
$villes = relierCSV($villes, $departements, $regions);

// Récupération des coordonnées depuis l’URL ou les cookies
$ville = $_GET['ville'] ?? ($_COOKIE['derniere_ville'] ?? null);
$lat = $_GET['lat'] ?? ($_COOKIE['derniere_lat'] ?? null);
$lon = $_GET['lon'] ?? ($_COOKIE['derniere_lon'] ?? null);

// Déduction de la région à partir de la ville
$region = $ville ? getRegionFromVille($ville, $villes, $departements, $regions) : "Région inconnue";

// Style dynamique selon le thème
$style_css = getStyle(); 
echo en_tete("Prévisions complètes - Météo", false);
?>

<title>Prévisions - Météo</title>
<link rel="stylesheet" href="dark-styles.css">

<main class="main-part">
    <section class="weather-section">
        <h1 class="site-title">
            <span class="icon">📅</span> Prévisions complètes 
            <?php if ($ville): ?>
                pour <span class="city"><?= htmlspecialchars($ville) ?></span> 
                <span class="region">(<?= htmlspecialchars($region) ?>)</span>
            <?php else: ?>
                - Ville inconnue
            <?php endif; ?>
        </h1>

        <?php if ($lat && $lon): ?>
            <?php
            // Récupération des données météo
            $data = getMeteoData((float)$lat, (float)$lon);

            // Extraction des prévisions journalières
            $jours = $data['daily']['time'];
            $tmin = $data['daily']['temperature_2m_min'];
            $tmax = $data['daily']['temperature_2m_max'];
            $ressMin = $data['daily']['apparent_temperature_min'];
            $ressMax = $data['daily']['apparent_temperature_max'];
            $pluie = $data['daily']['precipitation_sum'];
            $neige = $data['daily']['snowfall_sum'];
            $pluieH = $data['daily']['precipitation_hours'];
            $vent = $data['daily']['windspeed_10m_max'];
            $dirVent = $data['daily']['winddirection_10m_dominant'];
            $sunrise = $data['daily']['sunrise'];
            $sunset = $data['daily']['sunset'];
            $uv = $data['daily']['uv_index_max'];
            $codes = $data['daily']['weathercode'];

            // Icônes associées aux codes météo
            $icons = [
                0 => "☀️", 1 => "🌤️", 2 => "⛅", 3 => "☁️", 45 => "🌫️",
                51 => "🌦️", 61 => "🌧️", 71 => "❄️", 95 => "⛈️"
            ];
            ?>

            <!-- Affichage des prévisions sur 5 jours -->
            <div class="weather-grid">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="weather-card">
                        <h2 class="card-title"><?= date("D d M", strtotime($jours[$i])) ?></h2>
                        <div class="weather-icon"><?= $icons[$codes[$i]] ?? "❓" ?></div>
                        <p class="temp">
                            <span class="icon">🌡️</span> <?= $tmin[$i] ?>° / <?= $tmax[$i] ?>°
                        </p>
                        <p class="feels-like">
                            <span class="icon">🤒</span> Ressenti : <?= $ressMin[$i] ?>° / <?= $ressMax[$i] ?>°
                        </p>
                        <p class="precipitation">
                            <span class="icon">🌧️</span> Pluie : <?= $pluie[$i] ?> mm (<?= $pluieH[$i] ?>h)
                        </p>
                        <p class="snow">
                            <span class="icon">❄️</span> Neige : <?= $neige[$i] ?> mm
                        </p>
                        <p class="wind">
                            <span class="icon">💨</span> Vent : <?= $vent[$i] ?> km/h
                        </p>
                        <p class="wind-direction">
                            <span class="icon">🧭</span> Direction : <?= $dirVent[$i] ?>°
                        </p>
                        <p class="sun-times">
                            <span class="icon">☀️</span> 
                            <?= date("H:i", strtotime($sunrise[$i])) ?> - <?= date("H:i", strtotime($sunset[$i])) ?>
                        </p>
                        <p class="uv-index">
                            <span class="icon">🔆</span> UV : <?= $uv[$i] ?>
                        </p>
                    </div>
                <?php endfor; ?>
            </div>

        <?php else: ?>
            <!-- Message d’erreur si aucune localisation -->
            <p class="error-message">
                <span class="icon">⚠️</span> Aucune ville sélectionnée.
                Merci de revenir à l’accueil pour choisir une ville.
            </p>
        <?php endif; ?>

        <a href="index.php" class="btn">⬅ Retour à l'accueil</a>
    </section>
</main>

<?php require "./include/footer.inc.php"; ?>

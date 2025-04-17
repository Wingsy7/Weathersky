<?php 
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");

$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");
$villes = relierCSV($villes, $departements, $regions);

$ville = $_GET['ville'] ?? ($_COOKIE['derniere_ville'] ?? null);
$lat = $_GET['lat'] ?? ($_COOKIE['derniere_lat'] ?? null);
$lon = $_GET['lon'] ?? ($_COOKIE['derniere_lon'] ?? null);

$region = ($ville) ? getRegionFromVille($ville, $villes, $departements, $regions) : "Région inconnue";

$style_css = getStyle(); 
echo en_tete("Prévisions complètes - Météo", false);
echo TD_actuel_selectionné(1);
?>

<title>Prévisions - Météo</title>
<link rel="stylesheet" href="style.css">

<main>
    <div class="main-part">
        <h1>📅 Prévisions complètes pour <?= htmlspecialchars($ville) ?> (<?= $region ?>)</h1>

        <?php if ($lat && $lon): ?>
            <?php
            $data = getMeteoData((float)$lat, (float)$lon);
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

            $icons = [
                0 => "☀️", 1 => "🌤️", 2 => "⛅", 3 => "☁️", 45 => "🌫️",
                51 => "🌦️", 61 => "🌧️", 71 => "❄️", 95 => "⛈️"
            ];
            ?>

            <div class="container">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="day">
                        <h3><?= date("D d M", strtotime($jours[$i])) ?></h3>
                        <p><?= $icons[$codes[$i]] ?? "❓" ?></p>
                        <p>🌡️ <?= $tmin[$i] ?>° / <?= $tmax[$i] ?>°</p>
                        <p>🤒 Ressenti : <?= $ressMin[$i] ?>° / <?= $ressMax[$i] ?>°</p>
                        <p>🌧️ Pluie : <?= $pluie[$i] ?> mm (<?= $pluieH[$i] ?>h)</p>
                        <p>❄️ Neige : <?= $neige[$i] ?> mm</p>
                        <p>💨 Vent : <?= $vent[$i] ?> km/h</p>
                        <p>🧭 Direction : <?= $dirVent[$i] ?>°</p>
                        <p>☀️ <?= date("H:i", strtotime($sunrise[$i])) ?> - <?= date("H:i", strtotime($sunset[$i])) ?></p>
                        <p>🔆 UV : <?= $uv[$i] ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <p>Ville inconnue. Merci de revenir à l'accueil pour sélectionner une ville.</p>
        <?php endif; ?>

        <a href="index.php" class="btn">⬅ Retour à l'accueil</a>
    </div>
</main>

<?php require "./include/footer.inc.php"; ?>
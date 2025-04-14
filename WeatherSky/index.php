<?php 
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");
// Mise à jour du compteur de recherches par ville
if (isset($_GET['ville'])) {
    $villeStatsFile = "stats_villes.json";
    $stats = [];

    if (file_exists($villeStatsFile)) {
        $stats = json_decode(file_get_contents($villeStatsFile), true);
    }

    $villeRech = $_GET['ville'];
    if (!isset($stats[$villeRech])) {
        $stats[$villeRech] = 0;
    }
    $stats[$villeRech]++;

    file_put_contents($villeStatsFile, json_encode($stats, JSON_PRETTY_PRINT));
}


// 🔁 Lecture des fichiers CSV
$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");

// 📍 Position (GET > cookie > IP)
if (isset($_GET['ville'], $_GET['lat'], $_GET['lon'])) {
    $ville = $_GET['ville'];
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    setcookie("derniere_ville", $ville, time() + 7 * 24 * 3600);
    setcookie("derniere_lat", $lat, time() + 7 * 24 * 3600);
    setcookie("derniere_lon", $lon, time() + 7 * 24 * 3600);
} elseif (isset($_COOKIE['derniere_ville'], $_COOKIE['derniere_lat'], $_COOKIE['derniere_lon'])) {
    $ville = $_COOKIE['derniere_ville'];
    $lat = $_COOKIE['derniere_lat'];
    $lon = $_COOKIE['derniere_lon'];
} else {
    $location = getBestVisitorLocation();
    $ville = $location['city'] ?? 'Inconnue';
    $lat = $location['latitude'];
    $lon = $location['longitude'];
}

$style_css = getStyle(); 
echo en_tete("Cozma Miroslav - TDs de Développement Web", false);
echo TD_actuel_selectionné(0);
?>

<title>Météo</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    body { font-family: Arial; background: #f2f2f2; padding: 30px; text-align: center; }
    .container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
    .day {
        background: white; border-radius: 10px;
        padding: 15px; width: 230px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .day img { width: 60px; height: 60px; }
    #map { height: 300px; margin: 30px auto; width: 90%; border: 2px solid #ccc; border-radius: 10px; }
    #suggestions {
        list-style: none; margin: 0 auto; padding: 0; width: 300px; text-align: left;
        background: white; border: 1px solid #ccc; border-top: none; position: absolute; z-index: 999;
    }
    #suggestions li { padding: 8px; cursor: pointer; }
    #suggestions li:hover { background: #eee; }
    #departement {
        display: block; width: 100%; max-width: 400px;
        padding: 10px; font-size: 1rem;
        margin: 20px auto; border: 1px solid #ccc;
    }
    #previsions {
        margin-top: 40px; text-align: center;
    }
    .previsions-wrap {
        display: flex; justify-content: center; flex-wrap: wrap; gap: 15px;
    }
    .day-card {
        background: white; border-radius: 10px;
        padding: 15px; width: 180px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<main>
    <div class="main-part">
        <h1>🌤️ Météo personnalisée</h1>

        <h2>📍 Choisissez un département</h2>
        <form method="GET" action="" id="form-departement">
            <select id="departement" required>
                <option value="">-- Sélectionnez un département --</option>
            </select>
            <input type="hidden" name="ville" id="ville-dep">
            <input type="hidden" name="lat" id="lat-dep">
            <input type="hidden" name="lon" id="lon-dep">
            <button type="submit">Voir la météo</button>
        </form>

        <h2>🔍 Recherchez une ville</h2>
        <form id="form-ville" method="GET" action="">
            <input type="text" name="ville" id="ville-ville" placeholder="Entrez une ville..." autocomplete="off" required>
            <ul id="suggestions"></ul>
            <input type="hidden" name="lat" id="lat-ville">
            <input type="hidden" name="lon" id="lon-ville">
        </form>

        <div id="map"></div>
        <div id="previsions"></div>

        <?php if ($lat && $lon && $ville): ?>
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

            // Données pour le graphique JS
            $labelsJS = json_encode(array_map(fn($d) => date("D d", strtotime($d)), array_slice($jours, 0, 5)));
            $datasetJS = json_encode(array_slice($tmax, 0, 5));
            ?>

            <h2>📆 Météo sur 5 jours à <?= htmlspecialchars($ville) ?></h2>
            <div class="container">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="day">
                        <h3><?= date("D d M", strtotime($jours[$i])) ?></h3>
                        <img src="<?= getWeatherIcon($codes[$i]) ?>" alt="météo">
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

            <!-- 📊 Sitogramme des villes les plus recherchées -->
<canvas id="sitogramme" width="600" height="300"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
// Charger les stats depuis le fichier
$stats = json_decode(file_get_contents("stats_villes.json"), true);
$labelsJS = json_encode(array_keys($stats));
$dataJS = json_encode(array_values($stats));
?>
<script>
const ctx = document.getElementById('sitogramme').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= $labelsJS ?>,
        datasets: [{
            label: 'Nombre de recherches',
            data: <?= $dataJS ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // horizontal bars (optionnel)
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            title: {
                display: true,
                text: 'Sitogramme des villes les plus recherchées'
            }
        }
    }
});
</script>

        <?php endif; ?>
    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/script.js"></script>
<?php require "./include/footer.inc.php"; ?>

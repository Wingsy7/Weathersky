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

// Lecture des fichiers CSV
$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");

// Position (GET > cookie > IP)
if (isset($_GET['ville'], $_GET['lat'], $_GET['lon'])) {
    $ville = $_GET['ville'];
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $niveau = $_GET['niveau'] ?? 'general';
    setcookie("derniere_ville", $ville, time() + 7 * 24 * 3600);
    setcookie("derniere_lat", $lat, time() + 7 * 24 * 3600);
    setcookie("derniere_lon", $lon, time() + 7 * 24 * 3600);
} elseif (isset($_COOKIE['derniere_ville'], $_COOKIE['derniere_lat'], $_COOKIE['derniere_lon'])) {
    $ville = $_COOKIE['derniere_ville'];
    $lat = $_COOKIE['derniere_lat'];
    $lon = $_COOKIE['derniere_lon'];
    $niveau = $_GET['niveau'] ?? 'general';
} else {
    $location = getBestVisitorLocation();
    $ville = $location['city'] ?? 'Inconnue';
    $lat = $location['latitude'];
    $lon = $location['longitude'];
    $niveau = $_GET['niveau'] ?? 'general';
}

// Récupération des données météo dynamiques
$previsions = getPrevisions($lat, $lon);
$jours = $previsions['jours'];
$tmin = $previsions['tmin'];
$tmax = $previsions['tmax'];
$ressMin = $previsions['ressMin'];
$ressMax = $previsions['ressMax'];
$pluie = $previsions['pluie'];
$pluieH = $previsions['pluieH'];
$neige = $previsions['neige'];
$vent = $previsions['vent'];
$dirVent = $previsions['dirVent'];
$sunrise = $previsions['sunrise'];
$sunset = $previsions['sunset'];
$uv = $previsions['uv'];
$codes = $previsions['codes'];


$style_css = getStyle();
echo en_tete("Cozma Miroslav - TDs de Développement Web", false);

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
    #departement, #niveau {
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
            <select name="niveau" id="niveau">
                <option value="general" <?= ($niveau === 'general') ? 'selected' : '' ?>>Informations générales</option>
                <option value="detaille" <?= ($niveau === 'detaille') ? 'selected' : '' ?>>Affichage détaillé</option>
            </select>
            <button type="submit">Voir la météo</button>
        </form>

        <h2>🔍 Recherchez une ville</h2>
<form id="form-ville" method="GET" action="">
    <select id="region-select" required>
        <option value="">-- Sélectionnez une région --</option>
    </select>

    <select id="departement-select" required disabled>
        <option value="">-- Sélectionnez un département --</option>
    </select>

    <select name="ville" id="ville-select" required disabled>
        <option value="">-- Sélectionnez une ville --</option>
    </select>

    <input type="hidden" name="lat" id="lat-ville">
    <input type="hidden" name="lon" id="lon-ville">

    <select name="niveau" id="niveau">
        <option value="general" <?= ($niveau === 'general') ? 'selected' : '' ?>>Informations générales</option>
        <option value="detaille" <?= ($niveau === 'detaille') ? 'selected' : '' ?>>Affichage détaillé</option>
    </select>
    <button type="submit">Voir la météo</button>

        </form>

        <div id="map"></div>
        <div id="previsions"></div>

        <?php if ($lat && $lon && $ville): ?>
            <h2>📆 Météo sur 5 jours à <?= htmlspecialchars($ville) ?></h2>
            <div class="container">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="day">
                        <h3><?= date("D d M", strtotime($jours[$i])) ?></h3>
                        <p><?= getWeatherIcon($codes[$i]) ?></p>
                        <p>🌡️ <?= $tmin[$i] ?>° / <?= $tmax[$i] ?>°</p>
                        <?php if ($niveau === 'detaille'): ?>
                            <p>🤒 Ressenti : <?= $ressMin[$i] ?>° / <?= $ressMax[$i] ?>°</p>
                            <p>🌧️ Pluie : <?= $pluie[$i] ?> mm (<?= $pluieH[$i] ?>h)</p>
                            <p>❄️ Neige : <?= $neige[$i] ?> mm</p>
                            <p>💨 Vent : <?= $vent[$i] ?> km/h</p>
                            <p>🧭 Direction : <?= $dirVent[$i] ?>°</p>
                            <p>☀️ <?= $sunrise[$i] ?> - <?= $sunset[$i] ?></p>
                            <p>🔆 UV : <?= $uv[$i] ?></p>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

    </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/script.js"></script>
<?php require "./include/footer.inc.php"; ?>








































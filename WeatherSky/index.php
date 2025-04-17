<?php
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");

// Chargement des données
$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");
$villes = relierCSV($villes, $departements, $regions);

// Initialisation
$ville = null;
$lat = null;
$lon = null;
$niveau = $_GET['niveau'] ?? 'general';

// Statistiques de recherche
if (isset($_GET['ville'])) {
    $villeStatsFile = "stats_villes.json";
    $stats = file_exists($villeStatsFile) ? json_decode(file_get_contents($villeStatsFile), true) : [];
    $villeRech = $_GET['ville'];
    $stats[$villeRech] = ($stats[$villeRech] ?? 0) + 1;
    file_put_contents($villeStatsFile, json_encode($stats, JSON_PRETTY_PRINT));
}

// Détection de la position (ordre : GET > cookie > IP)
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

// Récupération météo si coordonnées présentes
$hasCoord = $lat && $lon && $ville;
$previsions = $hasCoord ? getPrevisions($lat, $lon) : null;

$style_css = getStyle();
echo en_tete("Cozma Miroslav - TDs de Développement Web", false);
?>

<title>Accueil - Météo</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="style.css" />

<main>
  <div class="main-part">
    <h1>🌤️ Bienvenue sur Météo France</h1>

    <!-- Sélection par département -->
    <section>
      <h2>📍 Recherche par département</h2>
      <form method="GET" action="" id="form-departement">
        <select id="departement" required>
          <option value="">-- Sélectionnez un département --</option>
        </select>
        <input type="hidden" name="ville" id="ville-dep">
        <input type="hidden" name="lat" id="lat-dep">
        <input type="hidden" name="lon" id="lon-dep">
        <select name="niveau" id="niveau-dep">
          <option value="general" <?= ($niveau === 'general') ? 'selected' : '' ?>>Informations générales</option>
          <option value="detaille" <?= ($niveau === 'detaille') ? 'selected' : '' ?>>Affichage détaillé</option>
        </select>
        <button type="submit">Voir la météo</button>
      </form>
    </section>

    <!-- Recherche par ville -->
    <section>
      <h2>🔍 Recherche par ville</h2>
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
        <select name="niveau" id="niveau-ville">
          <option value="general" <?= ($niveau === 'general') ? 'selected' : '' ?>>Informations générales</option>
          <option value="detaille" <?= ($niveau === 'detaille') ? 'selected' : '' ?>>Affichage détaillé</option>
        </select>
        <button type="submit">Voir la météo</button>
      </form>
    </section>

    <!-- Carte interactive -->
    <section>
      <h2>🗺️ Carte interactive</h2>
      <p>🔹 Cliquez sur une zone pour voir la météo du jour</p>
      <div id="map" style="height: 500px; width: 100%; border: 2px solid #ccc; border-radius: 10px;"></div>
    </section>

    <!-- Météo -->
    <section id="previsions">
      <?php if ($hasCoord && $previsions): ?>
        <h2>📆 Météo sur 5 jours à <?= htmlspecialchars($ville) ?></h2>
        <div class="container">
          <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="day">
              <h3><?= date("D d M", strtotime($previsions['jours'][$i])) ?></h3>
              <p><?= getWeatherIcon($previsions['codes'][$i]) ?></p>
              <p>🌡️ <?= $previsions['tmin'][$i] ?>° / <?= $previsions['tmax'][$i] ?>°</p>
              <?php if ($niveau === 'detaille'): ?>
                <p>🤒 Ressenti : <?= $previsions['ressMin'][$i] ?>° / <?= $previsions['ressMax'][$i] ?>°</p>
                <p>🌧️ Pluie : <?= $previsions['pluie'][$i] ?> mm (<?= $previsions['pluieH'][$i] ?>h)</p>
                <p>❄️ Neige : <?= $previsions['neige'][$i] ?> mm</p>
                <p>💨 Vent : <?= $previsions['vent'][$i] ?> km/h</p>
                <p>🧭 Direction : <?= $previsions['dirVent'][$i] ?>°</p>
                <p>☀️ <?= $previsions['sunrise'][$i] ?> - <?= $previsions['sunset'][$i] ?></p>
                <p>🔆 UV : <?= $previsions['uv'][$i] ?></p>
              <?php endif; ?>
            </div>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="js/script.js"></script>
<?php require "./include/footer.inc.php"; ?>


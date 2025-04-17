<?php 
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");

$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");
$villes = relierCSV($villes, $departements, $regions);

$ville = null;
$lat = null;
$lon = null;

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

if (isset($_GET['dep_code'])) {
    $codeDep = $_GET['dep_code'];
    $prefecture = getPrefectureParDepartement($codeDep, $villes);
    if ($prefecture) {
        $ville = $prefecture['nom'];
        $lat = $prefecture['lat'];
        $lon = $prefecture['lon'];
        setcookie("derniere_ville", $ville, time() + 7 * 24 * 3600);
        setcookie("derniere_lat", $lat, time() + 7 * 24 * 3600);
        setcookie("derniere_lon", $lon, time() + 7 * 24 * 3600);
        header("Location: index.php?ville=" . urlencode($ville) . "&lat=$lat&lon=$lon");
        exit;
    }
} elseif (isset($_GET['ville'], $_GET['lat'], $_GET['lon'])) {
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

$region = ($ville) ? getRegionFromVille($ville, $villes, $departements, $regions) : "Région inconnue";
$style_css = getStyle(); 
echo en_tete("Cozma Miroslav - TDs de Développement Web", false);
echo TD_actuel_selectionné(0);
?>
<title>Accueil - Météo</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="style.css">

<main>
  <div class="main-part">
    <h1>🌤️ Bienvenue sur Météo France</h1>

    <section>
      <h2>📍 Recherche par département</h2>
      <form method="GET" action="" id="form-departement">
        <select id="departement" required>
          <option value="">-- Sélectionnez un département --</option>
        </select>
        <input type="hidden" name="ville" id="ville-dep">
        <input type="hidden" name="lat" id="lat-dep">
        <input type="hidden" name="lon" id="lon-dep">
        <button type="submit">Voir la météo</button>
      </form>
    </section>

    <section>
      <h2>🔍 Recherche par ville</h2>
      <form id="form-ville" method="GET" action="">
        <input type="text" name="ville" id="ville-ville" placeholder="Entrez une ville..." autocomplete="off" required>
        <ul id="suggestions"></ul>
        <input type="hidden" name="lat" id="lat-ville">
        <input type="hidden" name="lon" id="lon-ville">
      </form>
    </section>

    <section>
      <h2>🗺️ Carte interactive</h2>
      <p>🔹 Cliquez sur une zone pour voir la météo du jour</p>
      <div id="map-departements" style="height: 500px; width: 100%; border: 2px solid #ccc; border-radius: 10px;"></div>
    </section>

    <section id="previsions">
      <!-- Prévisions météo injectées par JS -->
    </section>
  </div>
</main>

<script src="js/script.js"></script>
<?php require "./include/footer.inc.php"; ?>

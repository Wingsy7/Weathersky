<?php
// Inclusion des fichiers nécessaires
require "./include/header.inc.php";
require "./include/functions.inc.php";
require_once("./include/meteo.php");

// Chargement des données CSV (villes, départements, régions)
$villes = lireCSV("villes.csv");
$departements = lireCSV("departement.csv");
$regions = lireCSV("regions.csv");

// Fusion des fichiers CSV pour associer les villes à leur département et région
$villes = relierCSV($villes, $departements, $regions);

// Initialisation des variables
$ville = null;
$lat = null;
$lon = null;

// Enregistrement d'une statistique de recherche de ville
if (isset($_GET['ville'])) {
    $villeStatsFile = "stats_villes.json";
    $stats = [];

    // Lecture du fichier de statistiques s'il existe
    if (file_exists($villeStatsFile)) {
        $stats = json_decode(file_get_contents($villeStatsFile), true);
    }

    $villeRech = $_GET['ville'];
    // Incrémentation du compteur de la ville
    if (!isset($stats[$villeRech])) {
        $stats[$villeRech] = 0;
    }
    $stats[$villeRech]++;
    file_put_contents($villeStatsFile, json_encode($stats, JSON_PRETTY_PRINT));
}

// Cas 1 : l'utilisateur choisit un département => récupération de sa préfecture
if (isset($_GET['dep_code'])) {
    $codeDep = $_GET['dep_code'];
    $prefecture = getPrefectureParDepartement($codeDep, $villes);

    if ($prefecture) {
        $ville = $prefecture['nom'];
        $lat = $prefecture['lat'];
        $lon = $prefecture['lon'];

        // Enregistrement dans les cookies
        setcookie("derniere_ville", $ville, time() + 7 * 24 * 3600);
        setcookie("derniere_lat", $lat, time() + 7 * 24 * 3600);
        setcookie("derniere_lon", $lon, time() + 7 * 24 * 3600);

        // Redirection vers index.php avec les coordonnées
        header("Location: index.php?ville=" . urlencode($ville) . "&lat=$lat&lon=$lon");
        exit;
    }

// Cas 2 : les coordonnées sont passées dans l'URL
} elseif (isset($_GET['ville'], $_GET['lat'], $_GET['lon'])) {
    $ville = $_GET['ville'];
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];

    // Sauvegarde dans les cookies
    setcookie("derniere_ville", $ville, time() + 7 * 24 * 3600);
    setcookie("derniere_lat", $lat, time() + 7 * 24 * 3600);
    setcookie("derniere_lon", $lon, time() + 7 * 24 * 3600);

// Cas 3 : les coordonnées sont déjà présentes dans les cookies
} elseif (isset($_COOKIE['derniere_ville'], $_COOKIE['derniere_lat'], $_COOKIE['derniere_lon'])) {
    $ville = $_COOKIE['derniere_ville'];
    $lat = $_COOKIE['derniere_lat'];
    $lon = $_COOKIE['derniere_lon'];

// Cas 4 : aucune donnée → tentative de géolocalisation via IP
} else {
    $location = getBestVisitorLocation();
    $ville = $location['city'] ?? 'Inconnue';
    $lat = $location['latitude'];
    $lon = $location['longitude'];
}

// Récupération de la région correspondant à la ville sélectionnée
$region = ($ville) ? getRegionFromVille($ville, $villes, $departements, $regions) : "Région inconnue";

// Récupération du thème CSS actuel
$style_css = getStyle(); 

// Affichage de l'en-tête du site
echo en_tete("WeatherSky", false);

?>

<!-- En-tête de la page -->
<title>Accueil - Météo</title>
<!-- Feuilles de styles et Leaflet pour la carte -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="style.css" />

<!-- Contenu principal -->
<main>
  <div class="main-part">
    <h1>🌤️ Bienvenue sur Météo France</h1>

    <!-- Formulaire de recherche par département -->
    <section>
      <h2>📍 Recherche par département</h2>
      <form method="GET" action="" id="form-departement">
        <select id="departement" required>
          <option value="">-- Sélectionnez un département --</option>
        </select>
        <!-- Champs cachés pour le nom de ville + coordonnées -->
        <input type="hidden" name="ville" id="ville-dep" />
        <input type="hidden" name="lat" id="lat-dep" />
        <input type="hidden" name="lon" id="lon-dep" />
        <button type="submit">Voir la météo</button>
      </form>
    </section>

    <!-- Formulaire de recherche par ville -->
    <section>
      <h2>🔍 Recherche par ville</h2>
      <form id="form-ville" method="GET" action="">
        <input type="text" name="ville" id="ville-ville" placeholder="Entrez une ville..." autocomplete="off" required />
        <ul id="suggestions"></ul>
        <input type="hidden" name="lat" id="lat-ville" />
        <input type="hidden" name="lon" id="lon-ville" />
      </form>
    </section>

    <!-- Carte interactive -->
    <section>
      <h2>🗺️ Carte interactive</h2>
      <p>🔹 Cliquez sur une zone pour voir la météo du jour</p>
      <div id="map-departements" style="height: 500px; width: 100%; border: 2px solid #ccc; border-radius: 10px;"></div>
    </section>

    <!-- Prévisions météo à insérer dynamiquement -->
    <section id="previsions">
      <!-- Prévisions météo injectées par JS -->
    </section>
  </div>
</main>

<!-- Script principal JS -->
<script src="js/script.js"></script>

<?php
// Inclusion du pied de page
require "./include/footer.inc.php";
?>



































